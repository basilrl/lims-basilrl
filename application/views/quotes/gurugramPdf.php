<!DOCTYPE html>
<html lang="en">

<head>
    <title><?php echo ($data->reference_no) ? $data->reference_no : ""; ?></title>
</head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
    @page {
        background: url('https://cpslims-prod.s3.ap-south-1.amazonaws.com/format/quote_banner.png') no-repeat 0 0;
        margin-top: 3.0cm !important;
        margin-bottom: 3.0cm !important;
        background-position: center;
        background-repeat: no-repeat;
        width: 100%;
        opacity: 0.3;
        background-size: cover !important;
        font-size: 12px !important;
        font-family: "calibri" !important;
        header: html_myHeader;
        footer: html_myFooter;
    }



    table.content {
        text-align: center;
    }

    div.content {
        page-break-before: always;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    thead th,
    tbody td {
        border: 1px solid black;
    }

    tfoot td {
        text-align: right;
    }

    .test_table td,
    .test_table th {
        border: 1px solid black;
        border-collapse: collapse;
        align-self: center;


    }

    div.cust_details {
        page-break-before: always;
    }

    .post_details td {
        border: 1px solid black;
        border-collapse: collapse;
        align-self: center;
    }


    <?php //if (array_key_exists('about_us_details', $data)) { ?><?php //if (!empty($data->about_us_details) && $data->about_us_details != NULL) { ?>
        /* @page :first {
        header: html_firstpageheader;

    } */

    <?php //} } ?>
</style>

<body>

    <?php
    if ($data->version) {
        $version = $data->version;
        if ($version == 0) {
            $version_number = "";
        } else if ($version > 0) {
            $version_number = "_V." . $version;
        }
    } else {
        $version_number = "";
    }
    ?>
    <htmlpageheader name="firstpageheader" style="display:none">

    </htmlpageheader>

    <htmlpageheader name="myHeader">
        <table style="width:100%; text-align: right;">

            <tr>
                <td>
                    <img width="160px" height="50px" src="https://cpslims-prod.s3.ap-south-1.amazonaws.com/format/quote_logo.png" alt="">
                </td>
            </tr>

        </table>

    </htmlpageheader>

    <htmlpagefooter name="myFooter">
        <table style="width: 100%;">
            <tr>
                <td style="width:100%; text-align:center;color: grey;">M: <?php echo ($data->admin_email) ? ($data->admin_email) : ""; ?> | T: <?php echo ($data->admin_telephone) ? ($data->admin_telephone) : ""; ?> | W: portal.lims.basilrl.com</td>
            </tr>
            <tr>
                <td style="width:100%; text-align:center;color: grey;"> <?php echo ($data->branch_address) ? ($data->branch_address) : ""; ?></td>
            </tr>
        </table>

    </htmlpagefooter>
    <!-- about us -->
    <?php if ($data->allow_about_us == 1) { ?>
        <?php if (array_key_exists('about_us_details', $data)) { ?>

            <?php if (!empty($data->about_us_details) && $data->about_us_details != NULL) { ?>
                <p style="font-family:Calibri"><?php echo $data->about_us_details; ?></p>
                <div style="page-break-inside: auto !important;" class="cust_details">

        <?php }
        }
    } ?>

                </div>
                <!-- end -->

                <!-- introduction -->

                <?php if (array_key_exists('introduction', $data)) { ?>

                    <?php if (!empty($data->introduction) && $data->introduction != NULL) { ?>
                        <?php echo $data->introduction; ?>

                <?php }
                } ?>

                <!-- end -->
                <!-- end -->

                <?php $total_value = 0;
                if (array_key_exists('currency_code', $data)) {
                    $currency_code = $data->currency_code;
                } else {
                    $currency_code = '';
                }

                ?>
                <!-- test table details -->
                <?php if (!empty($test_data[0]->id)) : ?>
                    <?php if ($test_data && count($test_data) > 0) { ?>

                        <h3 style="text-align: center;">TEST DETAILS :</h3>
                        <table class="test_table content" style="margin-top:0px; margin-bottom:5px;">
                            <thead>
                                <tr>
                                    <th>Sr. No.</th>

                                    <?php if (array_key_exists('show_products', $data)) {
                                        if ($data->show_products == '1') { ?>

                                            <th>Product Name</th>

                                    <?php }
                                    } ?>




                                    <?php if (array_key_exists('show_division', $data)) {
                                        if ($data->show_division == '1') { ?>

                                            <th>Division Name</th>

                                    <?php }
                                    } ?>

                                    <th>Test Name</th>
                                    <?php if (array_key_exists('show_test_method', $data)) {
                                        if ($data->show_test_method == '1') { ?>
                                            <th>Test Method</th>
                                    <?php }
                                    } ?>

                                    <?php if (array_key_exists('show_price', $data)) {
                                        if ($data->show_price == '1') { ?>
                                            <th>Test Price (<?php echo $currency_code; ?>)</th>
                                    <?php }
                                    } ?>


                                    <?php if (array_key_exists('show_discount', $data)) {
                                        if ($data->show_discount == '1') { ?>

                                            <th>Discount</th>

                                    <?php }
                                    } ?>

                                    <th>Geo Chem Discounted Price Proposal (<?php echo $currency_code; ?>)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $sn = 1;
                                foreach ($test_data as $key => $value) { ?>
                                    <tr>
                                        <td><?php echo $sn; ?></td>

                                        <?php if (array_key_exists('show_products', $data)) {
                                            if ($data->show_products == '1') { ?>

                                                <td><?php echo ($value->sample_type_name) ? ($value->sample_type_name) : ""; ?></td>

                                        <?php }
                                        } ?>


                                        <?php if (array_key_exists('show_division', $data)) {
                                            if ($data->show_division == '1') { ?>

                                                <td><?php echo ($value->work_division_name) ? ($value->work_division_name) : ""; ?></td>
                                        <?php }
                                        } ?>
                                        <td><?php echo ($value->name) ? ($value->name) : "" ?></td>

                                        <?php if (array_key_exists('show_test_method', $data)) {
                                            if ($data->show_test_method == '1') { ?>
                                                <td><?php echo ($value->test_method) ? ($value->test_method) : "" ?></td>


                                        <?php }
                                        } ?>


                                        <?php if (array_key_exists('show_price', $data)) {
                                            if ($data->show_price == '1') { ?>
                                                <td><?php echo ($value->price) ? ($value->price) : 0.000 ?></td>


                                        <?php }
                                        } ?>

                                        <?php if (array_key_exists('show_discount', $data)) {
                                            if ($data->show_discount == '1') { ?>

                                                <td><?php echo ($value->discount) ? ($value->discount) : 0.000; ?></td>
                                        <?php }
                                        } ?>
                                        <td><?php echo ($value->applicable_charge) ? ($value->applicable_charge) : 0.000; ?></td>
                                    </tr>
                                    <?php $total_value += $value->applicable_charge; ?>
                                <?php $sn++;
                                } ?>
                            </tbody>
                        </table>
                    <?php } ?>

                <?php endif; ?>

                <?php if ($protocol_data && count($protocol_data) > 0) { ?>
                    <div style="page-break-before:always;">
                        <h3 style="text-align: center;">PROTOCOL DETAILS :</h3>
                        <h3 style="text-align: center;"><?php echo ($protocol_data['protocol_name']) ? $protocol_data['protocol_name'] : ""; ?></h3>

                        <table class="test_table content" style="margin-top:0px; margin-bottom:5px;">
                            <thead>
                                <tr>
                                    <th>Sl No</th>
                                    <th>Product Name</th>
                                    <th>Test</th>

                                    <?php if (array_key_exists('show_price', $data)) {
                                        if ($data->show_price) { ?>
                                            <th>Protocol Cost (<?php echo $currency_code; ?>)</th>

                                    <?php }
                                    } ?>


                                    <?php if (array_key_exists('show_discount', $data)) {
                                        if ($data->show_discount) { ?>
                                            <th>Discount</th>

                                    <?php }
                                    } ?>
                                    <th>Geo Chem Discounted Price Proposal (<?php echo $currency_code; ?>)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $sn = 1; ?>
                                <tr>
                                    <td><?php echo $sn; ?></td>
                                    <td><?php echo ($protocol_data['product_name']) ? $protocol_data['product_name'] : ""; ?></td>

                                    <td style="text-align: left;padding:10px">
                                        <?php foreach ($protocol_data['test_name'] as $i => $v) { ?>

                                            <p><?php echo $v ?></p><br>

                                        <?php } ?>
                                    </td>
                                    <?php if (array_key_exists('show_price', $data)) {
                                        if ($data->show_price) { ?>
                                            <td><?php echo ($protocol_data['cost']) ? $protocol_data['cost'] : ""; ?></td>


                                    <?php }
                                    } ?>

                                    <?php if (array_key_exists('show_discount', $data)) {
                                        if ($data->show_discount) { ?>
                                            <td><?php echo ($protocol_data['discount']) ? ($protocol_data['discount']) : 0; ?></td>

                                    <?php }
                                    } ?>
                                    <td><?php echo ($protocol_data['total_cost']) ? $protocol_data['total_cost'] : ""; ?></td>
                                </tr>
                                <?php $total_value += $protocol_data['total_cost'] ?>
                                <?php  ?>
                            </tbody>
                        </table>
                    </div>
                <?php } ?>
                <?php if ($package_data && count($package_data) > 0) { ?>
                    <div style="page-break-before:always;">
                        <h3 style="text-align: center;">PACKAGE DETAILS :</h3>
                        <h3 style="text-align: center;"><?php echo ($package_data['package_name']) ? $package_data['package_name'] : ""; ?></h3>
                        <table class="test_table content" style="margin-top:0px; margin-bottom:5px;">
                            <thead>
                                <tr>
                                    <th>Sl No</th>
                                    <th>Product Name</th>
                                    <th>Test</th>
                                    <?php if (array_key_exists('show_price', $data)) {
                                        if ($data->show_price) { ?>
                                            <th>Package Cost (<?php echo $currency_code; ?>)</th>
                                    <?php }
                                    } ?>
                                    <?php if (array_key_exists('show_discount', $data)) {
                                        if ($data->show_discount) { ?>
                                            <th>Discount</th>
                                    <?php }
                                    } ?>
                                    <th>Geo Chem Discounted Price Proposal (<?php echo $currency_code; ?>)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $sn = 1; ?>
                                <tr>
                                    <td><?php echo $sn; ?></td>
                                    <td><?php echo ($package_data['product_name']) ? $package_data['product_name'] : ""; ?></td>
                                    <td style="text-align: left;padding:10px">
                                        <?php foreach ($package_data['test_name'] as $i => $v) { ?>
                                            <p><?php echo $v ?></p><br>
                                        <?php } ?>
                                    </td>
                                    <?php if (array_key_exists('show_price', $data)) {
                                        if ($data->show_price) { ?>
                                            <td><?php echo ($package_data['cost']) ? $package_data['cost'] : ""; ?></td>
                                    <?php }
                                    } ?>
                                    <?php if (array_key_exists('show_discount', $data)) {
                                        if ($data->show_discount) { ?>
                                            <td><?php echo ($package_data['discount']) ? ($package_data['discount']) : 0; ?></td>
                                    <?php }
                                    } ?>
                                    <td><?php echo ($package_data['total_cost']) ? $package_data['total_cost'] : ""; ?></td>
                                </tr>
                                <?php $total_value += $package_data['total_cost'] ?>
                                <?php  ?>
                            </tbody>
                        </table>
                    </div>
                <?php } ?>
                <?php $sgst = round(($total_value / 100) * 9); ?>
                <?php $cgst = round(($total_value / 100) * 9); ?>

                <?php if (array_key_exists('show_total_amount', $data)) { ?>
                    <?php if ($data->show_total_amount == '1') { ?>

                        <table style="width: 100%;">
                            <tr>
                                <td width="50%"></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>_________________</td>
                                <td>_________________</td>
                            </tr><br>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>Total Amount</td>
                                <td><?php echo $total_value; ?></td>
                            </tr>
                        </table>
                    <?php } ?>
                <?php } ?>
                <!-- notes -->
                <?php if (array_key_exists('notes_details', $data)) : ?>
                    <?php if (!empty($data->notes_details) && ($data->notes_details != NULL)) : ?>
                        <?php echo $data->notes_details; ?>
                    <?php endif; ?>
                <?php endif; ?>
                <!-- end -->
                <!-- contact_details -->
                <?php if (array_key_exists('contact_details', $data)) : ?>
                    <?php if (!empty($data->contact_details) && ($data->contact_details != NULL)) : ?>
                        <?php echo $data->contact_details; ?>
                    <?php endif; ?>
                <?php endif; ?>
                <!-- end -->
                <table style="margin-top:50px;">
                    <tr>
                        <td style="width: 45%;">
                            <table>
                                <tr>
                                    <td colspan="2">Prepared by-</td>
                                </tr>

                                <tr>
                                    <td colspan="2"><?php echo ($data->quote_created_user) ? ($data->quote_created_user) : ""; ?></td>
                                </tr>
                                <tr>
                                    <td colspan='2'>
                                        <img src="<?php echo ($data->admin_signature) ? ($data->admin_signature) : ""; ?>" alt=" SIGNATURE" height="50px" width="200px">
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2"><?php echo ($data->designation_name) ? ($data->designation_name) : ""; ?> | CPS Division</td>
                                </tr>
                                <tr>
                                    <td colspan="2">GEO-CHEM LABORATORIES PVT. LTD.</td>
                                </tr>
                                <tr>
                                    <td>Telephone :</td>
                                    <td><?php echo ($data->branch_telephone) ? ($data->branch_telephone) : ""; ?> </td>
                                </tr>
                                <tr>
                                    <td>Mobile :</td>
                                    <td><?php echo ($data->admin_telephone) ? ($data->admin_telephone) : ""; ?></td>
                                </tr>
                                <tr>
                                    <td>Email :</td>
                                    <td><?php echo ($data->admin_email) ? ($data->admin_email) : ""; ?></td>
                                </tr>
                                <tr>
                                    <td>Website :</td>
                                    <td>
                                        <p>https://basilrl.com</p>
                                       
                                    </td>
                                </tr>
                                <tr>
                                    <td>Address :</td>
                                    <td><?php echo ($data->branch_address) ? ($data->branch_address) : ""; ?></td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 10%;">
                        </td>
                        <td style="width: 45%;">
                            <table>
                                <tr>
                                    <td colspan="2">Approved by -</td>
                                </tr>
                                <tr>
                                    <td colspan="2"><?php echo ($data->approver) ? ($data->approver) : "" ?></td>
                                </tr>
                                <tr>
                                    <td colspan='2'>
                                        <img src="<?php echo ($data->approver_signature) ? ($data->approver_signature) : ""; ?>" alt="APPROVER SIGNATURE" height="50px" width="200px">
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2"><?php echo ($data->approver_designation_name) ? ($data->approver_designation_name) : ""; ?> | CPS Division</td>
                                </tr>
                                <tr>
                                    <td colspan="2">GEO-CHEM LABORATORIES PVT. LTD.</td>
                                </tr>
                                <tr>
                                    <td>Telephone :</td>
                                    <td><?php echo ($data->branch_telephone) ? ($data->branch_telephone) : ""; ?></td>
                                </tr>
                                <tr>
                                    <td>Mobile :</td>
                                    <td><?php echo ($data->approver_telephone) ? ($data->approver_telephone) : ""; ?></td>
                                </tr>
                                <tr>
                                    <td>Email :</td>
                                    <td><?php echo ($data->approver_email) ? ($data->approver_email) : ""; ?></td>
                                </tr>
                                <tr>
                                    <td>Website :</td>
                                    <td>
                                        <p>https://basilrl.com</p>
                                       
                                    </td>
                                </tr>
                                <tr>
                                    <td>Address :</td>
                                    <td><?php echo ($data->branch_address) ? ($data->branch_address) : ""; ?></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
                <br>
                <!-- terms_details -->
                <!-- <?php if (array_key_exists('terms_details', $data)) : ?>
    <?php if (!empty($data->terms_details) && ($data->terms_details != NULL)) : ?>
        <?php echo $data->terms_details; ?>
    <?php endif; ?>
    <?php endif; ?> -->
                <br><br><br>
                <div class="term_conditions_fixed">
                    <h3 style="text-align: center;">TERMS AND CONDITIONS</h3>
                    <p style="font-size: 12px; text-align:justify">Geo-Chem Laboratories Pvt. Ltd. Undertakes to provide services (“Work(s)”) to its Customer Subject to the terms and conditions (“ Terms”) contained herein. The term of limitation of liability contained herein has been conspicuously marked to
                        draw to the attention of the customer. The customer is advised to take separate legal advice and is fully aware of the meaning and the legal significance of this term. The Customer agrees that this term is integral part of this Agreement.</p>
                    <ol style="font-size:12px;">
                        <li><b>INTERPRETATION</b>
                            <ol>
                                <li>In this Agreement the following words and phrases shall have the following meanings unless the context otherwise requires:
                                    <ol>
                                        <li><b>Agreement</b> means this agreement enter into between Geo Chem and the Client.</li>
                                        <li><b>Charges</b> shall have the meaning given in Clause6.1.</li>
                                        <li><b>Confidential Information</b> mean shall information in what every form or manner presented
                                            which: (a) is disclosed pursue in to or in the course of the provision of Services pursuant to, this Agreement; and (b) (i) is disclosed in writing, electronically, visually, or all other wise howsoever and is marked, stamped or identified by
                                            any means as confidential by the disclosing party at the time of such disclosure; and/or (ii) is information, howsoever disclosed, which would – reasonably be considered to be confidential by the receiving party.</li>
                                        <li><b>Intellectual Property Right(s)</b> means copyrights, trademarks (registered or unregistered), patents, patent applications (including the right to apply for a patent), service marks, design rights (register end-run registered), trade secrets
                                            and other like rights howsoever existing.</li>
                                        <li><b>Report(s)</b> shall have the meaning as set out in Clause 5.3below:</li>
                                        <li><b>Services</b> means these revises out in any relevant Geo Chem Proposal, any relevant Client purchase order ,or any relevant Geo Chem invoice, as applicable, and may comprise or include the provision by Geo Chem of a Report</li>
                                        <li><b>Proposal</b> means the proposal, estimate or fee quote, if applicable, provided to the Client by Geo Chem relating to the Services.</li>
                                    </ol>
                                </li>
                                <li>The headings in this Agreement do not affect its interpretation.</li>
                            </ol>
                        </li>
                        <li><b>COPYRIGHT COMPLIANCE OR INTELLECTUAL PROPERTY RIGHTS AND DATA PROTECTION</b>
                            <ol>
                                <li>All Intellectual Property Rights belonging to a party prior to entry into this Agreement shall remain vested in that party. Nothing in this Agreement is intended to transfer any Intellectual Property Rights from either party to the other</li>
                                <li>Any use by the Client (or the Client's affiliated companies or subsidiaries) of the name <b>"Geo Chem"</b> or any of <b>Geo Chem's</b> trademarks or brand names for any reason must be prior approved in writing by <b>Geo Chem</b>. Any other use of
                                    <b>Geo Chem‘s</b> trademarks or brand names is strictly prohibited and <b>Geo Chem</b> reserves the right to terminate this Agreement immediately as a result of any such unauthorized use.
                                </li>
                                <li>In the event of provision of certification services, Client agrees and acknowledges that the use of certification marks may be subject to national and international laws and regulations.</li>
                                <li>All Intellectual Property Rights in any Reports, document, graphs, charts, photographs or any other material (in whatever medium) produced by <b>Geo Chem</b> pursuant to this Agreement shall belong to <b>Geo Chem</b>. The Client shall have
                                    the right to use any such Reports, document, graphs, charts, photographs or other material for the purposes of this Agreement</li>
                                <li>The Client agrees and acknowledges that <b>Geo Chem</b> retains any and all proprietary rights in concepts, ideas and inventions that may arise during the preparation or provision of any Report (including any deliverables provided by <b>Geo
                                        Chem</b> to the Client) and the provision of the Services to the Client.</li>
                                <li><b>Geo Chem</b> shall observe all statutory provisions with regard to data protection including but not limited to the provisions of the Data Protection Act 1998. To the extent that <b>Geo Chem</b> processes or gets access to personal data in
                                    connection with the Services or otherwise in connection with this Agreement, it shall take all necessary technical and organizational measures to ensure the security of such data (and to guard against unauthorized or unlawful
                                    processing, accidental loss, destruction or damage to such data).</li>
                            </ol>
                        </li>
                        <li><b>OBLIGATION OF CUSTOMERS</b>
                            <ol>
                                <li>If the customer intends to make any change(s) to the Work(s) hereunder or assign any other work to Geo Chem QA prior to the completion of Work(s), such a change or new assignment shall only be effective in writing between both of
                                    the parties. If Geo Chem QA suffers from any loss or damage due to such a change or new assignment the Customer shall compensate the losses and damages</li>
                                <li>If the Work(s) undertaken by Geo Chem QA hereunder requires any assistance from the Customer. The Customer shall be obliged to provide all necessary and reasonable cooperation and assistance which Geo Chem QA may deem
                                    it. If the Work(s) undertaken by Geo Chem QA hereunder cannot be completed due to the Customer‟s failure or inadequacy in assistance or cooperation, Geo Chem QA reserves the right to require the Customer to perform its
                                    obligation within a specified period of time limit for Geo Chem QA to complete its work(s) shall be extended simultaneously. If upon the expiration of specified time period the Customer still fails to perform its obligation to assist Geo
                                    Chem QA reserves the right to terminate this Agreement without prejudice to any other rights of Geo Chem QA hereunder or under any applicable laws and regulations</li>
                            </ol>
                        </li>
                        <li><b>PATENT RIGHTS</b>
                            <ol>
                                <li>Any invention made in the performance of Work(s) for the Customer by Geo Chem QA within the field of Work(s) undertaken for the Customer shall belong to the Customer.</li>
                                <li>Geo Chem QA‟s use of the aforesaid inventions shall be free of any royalty fees or other changes provided that the uses of such inventions are confined to the performance of Work(s) for the Customer.</li>
                            </ol>
                        </li>

                        <li><b>THE SERVICES</b>
                            <ol>
                                <li>Geo Chem shall provide the Services to the Client in accordance with the terms of this Agreement which is expressly incorporated into any Proposal Geo Chem has made and submitted to the Client</li>
                                <li>In the even to any inconsistency between the terms of this Agreement and the Proposal, the terms of the Proposal shall take precedence.</li>
                                <li>The Services provided by Geo Chem under this Agreement and any memoranda, laboratory
                                    data, calculations, measurements, estimates notes, certificates and other material prepared by Geo Chem in the course of providing the Services to the Client, together with status summaries or any other communication in any form
                                    describing the results of any work or services performed <b>(Report(s))</b> shall be only for the Client's use and benefit</li>
                                <li>The Client acknowledges and agrees that if in providing the Services Geo Chem is obliged to deliver a Report to a third party, Geo Chem shall be deemed irrevocably authorized to deliver such Report to the applicable third party. For
                                    the purposes of this clause an obligation shall arise on the instructions of the Client, or where, in the reasonable opinion of Geo Chem, it is implicit from the circumstances, trade, custom, usage or practice</li>
                                <li>The Client acknowledges and agrees that any Services provided and/or Reports produced by Geo Chem are done so within the limits of the scope of work agreed with the Client in relation to the Proposal and pursuant to the Client's
                                    specific instructions or, in the absence of such instructions, in accordance with any relevant trade custom, usage or practice. The Client further agrees and acknowledges that the Services are not necessarily designed or intended to
                                    address all matters of quality, safety, performance or condition of any product, material, services, systems or processes tested, inspected or certified and the scope of work does not necessarily reflect all standards which may apply to
                                    product, material, services, systems or process tested, inspected or certified. The Client understands that reliance on any Reports issued by Geo Chem is limited to the facts and representations set out in the Reports which represent
                                    Geo Chem „s review and/or analysis of facts, information, documents, samples and/or other materials in existence at the time of the performance of the Services only.</li>
                                <li>Client is responsible for acting as its refit on the basis of such Report. Neither Geo Chem nor any of its officers, employees, agents or subcontract or shall be liable to Client nor any third party for any actions taken or not taken on the
                                    basis of such Report.</li>
                                <li>In agreeing to provide the Services pursuant to this Agreement, Geo Chem does not abridge, abrogate or undertake to discharge any duty or obligation of the Client to any other person or any duty or obligation of any person to the
                                    Client.</li>
                            </ol>
                        </li>
                        <li><b>CHARGES, INVOICING AND PAYMENT</b>
                            <ol>
                                <li>The Client shall pay <b>Geo Chem</b> the charges set out in the Proposal, if applicable, or as otherwise contemplated for provision of the Services (the Charges).</li>
                                <li>The Charges are expressed exclusive of any applicable taxes. The Client shall pay any applicable taxes on the Charges at the rate and in the manner prescribed by law, on the issue by Geo Chem of a valid invoice</li>
                                <li>The Client agrees that it will reimburse <b>Geo Chem</b> for any expenses incurred by <b>Geo Chem</b> relating to the provision of the Services and is wholly responsible for any freight or customs clearance fees relating to any testing samples.
                                </li>
                                <li>The Charges represent the total fees to be paid by the Client for the Services pursuant to this Agreement. Any additional work performed by <b>Geo Chem</b> will be charged on a time and material basis.</li>
                                <li><b>Geo Chem</b> shall invoice the Client for the Charges and expenses, if any. The Client shall pay each invoice within thirty (30) days of receiving it</li>
                                <li>If any invoice is not paid on the due date for payment, <b>Geo Chem</b> shall have the right to charge, and the Client shall pay, interest on the unpaid amount, calculated from the due date of the invoice to the date of receipt of the amount in
                                    full at a rate equivalent to 3% per cent per annum above the base rate</li>
                            </ol>
                        </li>
                        <li><b>DATA AND DOCUMENT RETENTION</b>
                            <ol>
                                <li>After the work(s) rendered, Geo Chem QA may retain a copy of all documents relating to the Work(s) (“the supporting documents”) for as long as Geo Chem QA gets the sole discretion.</li>
                                <li>Unless otherwise specified or required by the applicable law, the Supporting Documents over three years of age will be automatically destroyed by Geo Chem QA without prior notice to the customer. Should any or a supporting
                                    documents less than three years are scheduled to be destroyed GEO Chem QA shall give the Customer thirty days written notice to the Customer‟s last known address of the intention to destroy the Supporting Documents. Unless the
                                    Customer makes a written request to Geo Chem QA which is received by Geo Chem QA before the expiration of the said thirty days seeking delivery of those documents to the Customer at the Customer‟s expenses, those documents
                                    shall be destroyed.</li>
                                <li>The Customer shall indemnify Geo Chem QA for any costs or expenses in responding to or opposing any claims or losses or for the production of any documents in Court seeking the disclosure of the said documents or any information
                                    contained therein.</li>
                            </ol>
                        </li>
                        <li><b>INDEMNITY</b>
                            <ol>
                                <li>The Client shall in dignify and hold harmless Geo Chem, its officers, employees, agents, representatives, contractors and sub-contractors from and against any and all claims, results, liabilities (including costs of litigation and attorney's
                                    fees) arising, directly or indirectly, out of or in connection with:
                                    <ol>
                                        <li>Any claims results by any government all authority or others for any actual or as serrated failure of the Client to comply with any law, ordinance, regulation, rule or order of any government all or judicial authority;</li>
                                        <li>Claims results for personal injuries, loss of or damage to property, economic loss, and loss of or damage to Intellectual Property rights incurred by or occurring to any person or entity and arising in connection with or related to the
                                            Services provided here under by Geo Chem, its officers, employees, agents, representatives, contractors an sub-contractors;</li>
                                        <li>The breach or alleged breach by the Client of any of its obligations set out in Clause4 above;</li>
                                        <li>Any claims made by any third party for loss, damage or expense of what so ever nature and howsoever arising relating to the performance, purported performance or non- performance of any Services to the extent that the aggregate
                                            of any such claims relating to any one Service exceeds the limit of liability set out in Clause 10 above;
                                        </li>
                                        <li>Any claims results arising as result of any misuse run authorized use of any Reports issued by Geo Chem or any Intellectual Property Rights belonging to Geo Chem (including trade marks) pursuant to this Agreement; and
                                        </li>
                                        <li>Any claims arising out of or relating to any third party's use of or reliance on any Reports or any reports, analyses, conclusions of the Client (or any third party to whom the Client has provided the Reports) based in whole or in part on
                                            the Reports, if applicable.</li>
                                    </ol>
                                </li>
                                <li>The obligations set out in this Clause 11 shall survive termination of this Agreement.</li>
                            </ol>
                        </li>
                        <li><b>TERMINATION</b>
                            <ol>
                                <li>This Agreement shall commence upon the first day on which the Services are commenced and shall continue, unless terminated earlier in accordance with this Clause 13, until the Services have been provided.
                                </li>
                                <li>This Agreement may be terminated by:
                                    <ol>
                                        <li>Either party if the other continues in material breach of any obligation imposed up on it here under for more than thirty (30) days after written notice has been dispatched by that Party by recorded delivery or courier requesting the
                                            other to remedy such breach;</li>
                                        <li>Geo Chem on written notice to the Client in the event that the Client fails to pay any invoice by its due date and/or fails to make payment after a further request for payment; or</li>
                                        <li>Either party on writ ten notice to the other in the event that the other makes any voluntary arrangement with its creditors or becomes subject to an administration order or (being an individual or firm) becomes bankrupt or (being a
                                            company) goes into liquidation (otherwise than for the purposes of a solvent amalgamation or reconstruction) or an encumbrance takes possession, or a receiver is appointed, of any of the property or assets of the other or the other
                                            ceases, or threatens to cease, to carry on business.</li>
                                    </ol>
                                </li>
                                <li>In the event of termination of the Agreement for any reason and without prejudice to any other rights or remedies the parties may have, the Client shall pay Geo Chem for all Services performed up to the date of termination. This
                                    o b l i g a t i o n s h a l l s u r v i v e termination or expiration of this Agreement.
                                </li>
                                <li>Any termination or expiration of the Agreement shall not affect the accrued rights and obligations of the parties nor shall it affect any provision which is expressly or by implication intended to come in to force or continue in force on or
                                    after such termination or expiration.</li>
                            </ol>
                        </li>
                        <li><b>ASSIGNMENT AND SUB-CONTRACTING</b>
                            <ol>
                                <li>G e o C hem reserves the right to delegate the performance of its obligations here under and the provision of the Services to one or more of its affiliates and/or sub-contractors when necessary. G eo C h em may also assign this
                                    Agreement to any company within the G eo C h em group on notice to the Client.</li>
                            </ol>
                        </li>
                        <li><b>MISCELLANEOUS</b><br><b>Severability</b>
                            <ol>
                                <li>If any provision of this Agreement is or becomes invalid, illegal or unenforceable, such provision shall be severed and the remainder of the provisions shall continue in full force and effect as if this Agreement had been executed
                                    without the invalid illegal or unenforceable provision. If the in validity, illegality or unenforceability is so fundamental that it prevents the accomplishment of the purpose of this Agreement, Geo Chem and the Client shall immediately
                                    commence good faith negotiations to agree an alternative arrangement. <br><b>No partnership or agency</b></li>
                                <li>Nothing in this Agreement and no action taken by the parties under this Agreement shall constitute a partnership, association, joint venture or other co-operative entity between the parties or constitute any party the partner, agent or
                                    legal representative of the other. <b>Waivers</b></li>
                                <li>The failure of any party to insist upon strict performance of any provision of this Agreement, or to exercise any right or remedy to which it is entitled, shall not constitute a waiver and shall not cause a diminution of the obligations
                                    established by this Agreement. A waiver of any breach shall not constitute a waiver of any subsequent breach.</li>
                                <li>No waiver of any right or remedy under this Agreement shall be effective unless it is expressly stated to be a waiver and communicated to the other party in writing. <br><b>Whole Agreement</b>
                                </li>
                                <li>This Agreement and the Proposal contain the whole agreement between the parties relating to the transactions contemplated by this agreement and supersedes all previous agreements, arrangements and understandings between
                                    the parties relating to those transactions or that subject matter. No purchase order, statement or other similar document will add to or vary the terms of this Agreement</li>
                                <li>Each party acknowledges that in entering in to this Agreement it has not relied on any representation, warranty, collateral contractor other assurance (except those set out or refer red to in this Agreement) made by or on behalf of any
                                    other party before the acceptance or signature of this Agreement. Each party waives all rights and remedies that, but for this Clause, might otherwise be available to it in respect of any such representation, warranty, collateral
                                    contract or other assurance.</li>
                                <li>Nothing in this Agreement limits or excludes any liability for fraudulent misrepresentation. <br><b>Third Party Rights</b></li>
                                <li>A person who is not party to this Agreement has no right under the Contract (Rights of Third Parties) Act 1999 to enforce any of its terms. <br><b>Further Assurance</b></li>
                                <li>Each party shall, at the cost and request of any other party, execute and deliver such instruments and documents and take such other actions in each cases may be reasonably requested from time to time in order to give full effect to
                                    its obligations under this Agreement.</li>
                            </ol>
                        </li>
                        <li><b>AMENDMENT</b>
                            <ol>
                                <li>No amendment to this Agreement shall be effective unless it is in writing, expressly stated to amend this Agreement and signed by an authorized signatory of each party</li>
                            </ol>
                        </li>
                        <li><b>GOVERNING LAW</b>
                            <ol>
                                <li>The Agreement and the rights and obligation of the parties shall in all respects be governed, constructed, interpreted and operated with Indian Law.
                                    These General Conditions shall be Governed and construed in accordance with the substantive laws of the place where the Company renders services and issues reports or certificates, exclusive of any rules with respect of conflicts of
                                    laws. All Disputes arising in connection with these General Conditions shall be finally settled by recourse to arbitration under the rules of conciliation and arbitration of the International Chamber of Commerce by one or more arbitrators
                                    appointed in accordance with the said rules. Unless otherwise agreed, the arbitration shall take place in the English language at the place where the Company renders services and issues reports or certificates.
                                    the said rules. Unless otherwise agreed, the arbitration shall take place in the English language at the place where the Company renders services and issues reports or certificates</li>
                            </ol>
                        </li>
                    </ol>
                </div>
                <!-- end -->
                </div>
</body>
</html>