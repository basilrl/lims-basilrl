<!DOCTYPE html>
<html lang="en">
<title><?php echo ($data->reference_no) ? $data->reference_no : ""; ?></title>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .post_details td,
        .test_table td,
        .test_table th {
            border: 1px solid black;
            border-collapse: collapse;
            align-self: center;
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

        ol ol {
            padding: 0;
        }

        ol {
            counter-reset: item;
            list-style-position: inside;
        }

        ol li {
            display: block;
            position: relative;
        }

        ol li:before {
            content: counters(item, ".")".";
            counter-increment: item;
            position: absolute;
            margin-right: 100%;
            right: 10px;
            /* space between number and text */
        }

        div.chapter2 {
            page-break-before: always;
            page: noheader;
        }

        .parent_list {
            font-size: 5px;
        }

        .first {
            font-size: 6px;
        }

        .details {
            font-size: 10px;
        }

        p {
            line-height: 0;
        }

        .paiment {
            page-break-before: always;
        }

        table td {
            text-align: center;
        }

        @page {

            margin-top: 3.0cm !important;
            margin-bottom: 2cm !important;
            background-position: top center;
            header: html_myHeader1;
            footer: html_myFooter;

        }

        @page noheader {
            background: url('https://cpslims-prod.s3.ap-south-1.amazonaws.com/format/quote_pdf_background.png') no-repeat 0 0;
            background-repeat: no-repeat;
            width: 100%;
            opacity: 0.3;
            background-size: cover !important;
            header: html_myHeader2;
            footer: html_myFooter2;

        }
    </style>
</head>

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

<body>

    <htmlpageheader name="myHeader1">
        <table style="width:100%;text-align: left;">

            <tr>
                <td style="text-align: center;">
                    <img src="<?php echo base_url('public/img/logo/logo-login.png') ?>" alt="">
                </td>
            </tr>

        </table>

    </htmlpageheader>

    <htmlpageheader name="myHeader2">
        <table style="width:100%; text-align: right;">

            <tr>
                <td>
                    <img width="80px" height="25px" src="<?php echo base_url('public/img/logo/logo-login.png') ?>" alt="">
                </td>
            </tr>

        </table>

    </htmlpageheader>
    <htmlpagefooter name="myFooter">
        <table>

            <tr>
                <td style="width:85%">&nbsp;</td>
                <td style="float:right;"> Pages {PAGENO} of {nb}</td>
            </tr>
        </table>
    </htmlpagefooter>

    <htmlpagefooter name="myFooter2">
        <table style="width: 100%;">
            <tr>
                <td style="width:85%">&nbsp;</td>
                <td style="float:right;"> Pages {PAGENO} of {nb}</td>
            </tr>
        </table>

    </htmlpagefooter>
    <h3 style="text-align: center;">QUOTATION</h3>

    <div class="details">

        <table class='post_details'>
            <tr>
                <td colspan="2"><b>TO : <?php echo ($data->customer_name) ? ($data->customer_name) : ""; ?></b></td>
            </tr>

            <tr>
                <td><b>ATTN : </b>&nbsp;&nbsp;<?php echo ($data->contact_salutation) ? ($data->contact_salutation) : ""; ?><?php echo ($data->contact_name) ? ($data->contact_name) : "" ?><br>&nbsp;&nbsp;<?php echo ($data->contacts_designation_id) ? ($data->contacts_designation_id) : "" ?></td>

                <td><b>FROM : </b><?php echo ($data->quote_created_user) ? ($data->quote_created_user) : "" ?><br>
                    <?php echo ($data->designation_name) ? ($data->designation_name) : "" ?>
                </td>
            </tr>
            <tr>
                <td><b>QUOTE NO : </b>&nbsp;&nbsp;<?php echo ($data->reference_no) ? ($data->reference_no) : "" ?></td>
                <td><b>PAGES : </b>{nb}</td>
            </tr>
            <tr>
                <td><b>DATE : </b>&nbsp;&nbsp;<?php echo ($data->quote_date) ? ($data->quote_date) : "" ?></td>
                <td><b>TEL NO : </b>&nbsp;&nbsp;<?php echo ($data->admin_telephone) ? ($data->admin_telephone) : "" ?></td>
            </tr>
            <tr>
                <td>
                    <p><b>ADDRESS : </b>&nbsp;&nbsp;<?php echo ($data->address) ? ($data->address) : "" ?></p>
                    <p><b>Tel :</b>&nbsp;&nbsp;<?php echo ($data->telephone) ? ($data->telephone) : "" ?></p>
                    <p><b>Email :</b>&nbsp;&nbsp;<?php echo ($data->email) ? ($data->email) : ""  ?></p>
                </td>
                <td>
                    <b>EMAIL : &nbsp;&nbsp; <?php echo ($data->admin_email) ? ($data->admin_email) : "" ?></b>
                </td>
            </tr>
            <tr>
                <td><b>Discussion Dated :</b>&nbsp;&nbsp;<?php echo ($data->discussion_date) ? ($data->discussion_date) : "" ?><?php echo $version_number ?></td>
                <td><b>Quote Validity :</b>&nbsp;&nbsp;<?php echo ($data->quote_valid_date) ? ($data->quote_valid_date) : "" ?></td>
            </tr>
            <tr>
                <td colspan="2"><b>Remarks : </b><?php echo (html_entity_decode($data->additional_notes)) ? (html_entity_decode($data->additional_notes)) : ""; ?></td>
            </tr>
            <tr>
                <td colspan="2"><b>Buyer/self reference : </b><?php echo ($data->buyer_self_ref) ? ($data->buyer_self_ref) : ""; ?></td>
            </tr>

        </table>
        <p>With reference to your inquiry, please find below our offer for sample testing</p>
        <?php $total_value = 0; ?>
        <!-- test table details -->

    <?php if(!empty($test_data[0]->id)):?>
        <?php if ($test_data && count($test_data) > 0) { ?>
            
            <h3 style="text-align: center;">TEST DETAILS :</h3>
            <table class="test_table" style="margin-top:0px;">
                <thead>
                    <tr>
                        <th>Sl No</th>
                        <th>Product Name</th>
                        <th>Division Name</th>
                        <th>Test</th>
                        <th>Test Unit</th>
                        <th>Price</th>
                        <?php if (array_key_exists('show_discount', $data)) {
                            if ($data->show_discount == '1') { ?>
                                <th>Discount</th>

                        <?php }
                        } ?>

                        <th>Geo Chem Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $sn = 1;
                    foreach ($test_data as $key => $value) { ?>
                        <tr>
                            <td><?php echo $sn; ?></td>
                            <td><?php echo ($value->sample_type_name) ? ($value->sample_type_name) : ""; ?></td>
                            <td><?php echo ($value->work_division_name) ? ($value->work_division_name) : ""; ?></td>
                            <td><?php echo ($value->name) ? ($value->name) : "" ?></td>
                            <td><?php echo 1; ?></td>
                            <td><?php echo ($value->price) ? ($value->price) : 0.000 ?></td>
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

<?php endif;?>

        <?php if ($protocol_data && count($protocol_data) > 0) { ?>

            <h3 style="text-align: center;">PROTOCOL DETAILS :</h3>
            <h3 style="text-align: center;"><?php echo ($protocol_data['product_name']) ? $protocol_data['product_name'] : ""; ?></h3>

            <table class="test_table" style="margin-top:0px;">
                <thead>
                    <tr>
                        <th>Sl No</th>
                        <th>Protocol Name</th>
                        <th>Test</th>
                        <th>Protocol Cost (INR)</th>

                        <?php if (array_key_exists('show_discount', $data)) {
                            if ($data->show_discount) { ?>
                                <th>Discount</th>

                        <?php }
                        } ?>
                        <th>Geo Chem Discounted Price Proposal (INR)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $sn = 1; ?>
                    <tr>
                        <td><?php echo $sn; ?></td>
                        <td><?php echo ($protocol_data['product_name']) ? $protocol_data['product_name'] : ""; ?></td>
                        <td><?php echo ($protocol_data['test_name']) ? $protocol_data['test_name'] : ""; ?></td>
                        <td><?php echo ($protocol_data['cost']) ? $protocol_data['cost'] : ""; ?></td>

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
        <?php } ?>


        <?php if ($package_data && count($package_data) > 0) { ?>

            <h3 style="text-align: center;">PACKAGE DETAILS :</h3>
            <h3 style="text-align: center;"><?php echo ($package_data['package_name']) ? $package_data['package_name'] : ""; ?></h3>

            <table class="test_table" style="margin-top:0px;">
                <thead>
                    <tr>
                        <th>Sl No</th>
                        <th>Package Name</th>
                        <th>Test</th>
                        <th>Package Cost (INR)</th>

                        <?php if (array_key_exists('show_discount', $data)) {
                            if ($data->show_discount) { ?>
                                <th>Discount</th>

                        <?php }
                        } ?>
                        <th>Geo Chem Discounted Price Proposal (INR)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $sn = 1; ?>
                    <tr>
                        <td><?php echo $sn; ?></td>
                        <td><?php echo ($package_data['product_name']) ? $package_data['product_name'] : ""; ?></td>
                        <td><?php echo ($package_data['test_name']) ? $package_data['test_name'] : ""; ?></td>
                        <td><?php echo ($package_data['cost']) ? $package_data['cost'] : ""; ?></td>

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
        <?php } ?>


        <?php $sgst = round(($total_value / 100) * 9); ?>
        <?php $cgst = round(($total_value / 100) * 9); ?>

        <br><br>
        <table style="width: 100%;">

            <tr>
                <td width='50%'></td>
                <td></td>
                <td></td>
                <td>Total (net of tax)</td>
                <td><?php echo $total_value; ?></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td>SGST @ 9%</td>
                <td><?php echo $sgst; ?></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td>CGST @ 9%</td>
                <td><?php echo $cgst; ?></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td>_________________</td>
                <td>_________________</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td>Total (inc.tax)</td>
                <td><?php echo ($total_value + $sgst + $cgst) ?></td>
            </tr>

        </table>

        <table style="width: 100%;text-align:left;">
            <tr>
                <td style="text-align: left;">
                    <img src="<?php echo ($data->approver_signature) ? ($data->approver_signature) : ""; ?>" alt="APPROVER SIGNATURE">
                </td>
                    </tr>
                    <tr>
                <td style="text-align: left;">
                    <?php echo ($data->approver) ? ($data->approver) : "" ?>
                </td>
            </tr>
            <tr>
                <td style="text-align: left;">
                    Approver
                </td>
                
            </tr>

        </table>

        <!-- <table style="width: 100%;text-align:right;">
        <tr></tr>
    </table> -->
        <div class='paiment' style="page-break-inside: auto !important;font-size:10px;">
            <p><b>PAN Number</b> : AAACG1884B</p>
            <p><b>GSTIN Number</b> : 06AAACG1884B1Z0</p>
            <p><b>Payment term</b> : Payment must be made by draft/Cheque 'Payee's Account Only' in favour of: Basil Research Laboratories Pvt. Ltd.</p>
            <p><b>Details For payment by NEFT/RTGS/IFSC or PAY ORDER :</b></p>
            <p><b>Account Holder Name</b> : Basil Research Laboratories PVT LTD</p>
            <p><b>Account Number</b> : 039305008622</p>
            <p><b>Bank</b> : ICICI BANK LTD</p>
            <p><b>IFSC</b> : ICIC0000393</p>
            <p><b>Branch</b> : Parekh Marg, Reclamation Churchgate Mumbai - 400020</p>
            <p><i><b>NOTE</b> : THIS IS AN OFFICIAL QUOTATION, NO AUTHORIZED SIGNATURE IS REQUIRED.</i></p>
            <p>Thanks and Regards,</>
            <p>Satedar Shuka</p>
            <p>Gurgaon</p>
            <p>Tel : +91 9999484023</p>
            <p>Email : <a href="mailto:customercoordinator01@basilrl.com">customercoordinator01@basilrl.com</a></p>
        </div>

    </div>

    <div style="page-break-inside: auto !important;font-size:10px;" class="chapter2 noheader">
        <h3 style="text-align: center;">TERMS AND CONDITIONS</h3>

        <p class='first'>Geo-Chem Laboratories Pvt. Ltd. Undertakes to provide services (“Work(s)”) to its Customer Subject to the terms and conditions (“Terms”) contained herein. The term of limitation of liability contained herein has been conspicuously marked to
            draw to the attention of the customer. The customer is advised to take separate legal advice and is fully aware of the meaning and the legal significance of this term. The Customer agrees that this term is integral part of this Agreement.</p>


        <ol class="parent_list">
            <li><b>INTERPRETATION</b>
                <ol type="">
                    <li>In this Agreement the following words and phrases shall have the following meanings unless the context otherwise requires:
                        <ol class="sub_ol">
                            <li><b>Agreement</b> means this agreement enter into between Geo Chem and the Client;</li>
                            <li><b>Charges</b> shall have the meaning given in Clause6.1;</li>
                            <li><b>Confidential Information</b> mean shall information in what every form or manner presented
                                which:(a) is disclosed pursue in to or in the course of the provision of Services pursuant to, this Agreement; and (b) (i) is disclosed in writing, electronically, visually, or all other wise howsoever and is marked, stamped or identified by
                                any means as confidential by the disclosing party at the time of such disclosure; and/or (ii) is information, howsoever disclosed, which would – reasonably be considered to be confidential by the receiving party.
                            </li>
                            <li><b>Intellectual Property Right(s) means</b> copyrights, trademarks (registered or unregistered), patents, patent applications (including the right to apply for a patent), service marks, design rights (register end-run registered), trade secrets
                                and other like rights howsoever existing.</li>
                            <li><b>Report(s)</b> shall have the meaning as set out in Clause 5.3below;</li>
                            <li><b>Services</b> means these revises out in any relevant Geo Chem Proposal, any relevant Client purchase order ,or any relevant Geo Chem invoice, as applicable, and may comprise or include the provision by Geo Chem of a Report;</li>
                            <li><b>Proposal</b> means the proposal, estimate or fee quote, if applicable, provided to the Client by Geo Chem relating to the Services;</li>
                        </ol>
                    </li>
                    <li>The headings in this Agreement do not affect its interpretation.</li>
                </ol>
            </li>


            <li><b>COPYRIGHT COMPLIANCE OR INTELLECTUAL PROPERTY RIGHTS AND DATA PROTECTION</b>
                <ol>
                    <li>All Intellectual Property Rights belonging to a party prior to entry into this Agreement shall remain vested in that party. Nothing in this Agreement is intended to transfer any Intellectual Property Rights from either party to the other.</li>
                    <li>Any use by the Client (or the Client's affiliated companies or subsidiaries) of the name "Geo Chem" or any of Geo Chem's trademarks or brand names for any reason must be prior approved in writing by Geo Chem. Any other use of
                        Geo Chem‘s trademarks or brand names is strictly prohibited and Geo Chem reserves the right to terminate this Agreement immediately as a result of any such unauthorized use.</li>
                    <li>In the event of provision of certification services, Client agrees and acknowledges that the use of certification marks may be subject to national and international laws and regulations.</li>
                    <li>All Intellectual Property Rights in any Reports, document, graphs, charts, photographs or any other material (in whatever medium) produced by Geo Chem pursuant to this Agreement shall belong to Geo Chem. The Client shall have
                        the right to use any such Reports, document, graphs, charts, photographs or other material for the purposes of this Agreement.</li>
                    <li>The Client agrees and acknowledges that Geo Chem retains any and all proprietary rights in concepts, ideas and inventions that may arise during the preparation or provision of any Report (including any deliverables provided by <b>Geo
                            Chem</b> to the Client) and the provision of the Services to the Client.</li>
                    <li><b>Geo Chem</b> shall observe all statutory provisions with regard to data protection including but not limited to the provisions of the Data Protection Act 1998. To the extent that Geo Chem processes or gets access to personal data in
                        connection with the Services or otherwise in connection with this Agreement, it shall take all necessary technical and organizational measures to ensure the security of such data (and to guard against unauthorized or unlawful
                        processing, accidental loss, destruction or damage to such data).</li>
                </ol>
            </li>

            <li><b>OBLIGATION OF CUSTOMERS</b>
                <ol>
                    <li>If the customer intends to make any change(s) to the Work(s) hereunder or assign any other work to Geo Chem QA prior to the completion of Work(s), such a change or new assignment shall only be effective in writing between both of
                        the parties. If Geo Chem QA suffers from any loss or damage due to such a change or new assignment the Customer shall compensate the losses and damages.</li>
                    <li>If the Work(s) undertaken by Geo Chem QA hereunder requires any assistance from the Customer. The Customer shall be obliged to provide all necessary and reasonable cooperation and assistance which Geo Chem QA may deem
                        it. If the Work(s) undertaken by Geo Chem QA hereunder cannot be completed due to the Customer‟s failure or inadequacy in assistance or cooperation, Geo Chem QA reserves the right to require the Customer to perform its
                        obligation within a specified period of time limit for Geo Chem QA to complete its work(s) shall be extended simultaneously. If upon the expiration of specified time period the Customer still fails to perform its obligation to assist Geo
                        Chem QA reserves the right to terminate this Agreement without prejudice to any other rights of Geo Chem QA hereunder or under any applicable laws and regulations.</li>
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
                    <li>Geo Chem shall provide the Services to the Client in accordance with the terms of this Agreement which is expressly incorporated into any Proposal Geo Chem has made and submitted to the Client.</li>
                    <li>In the even to any inconsistency between the terms of this Agreement and the Proposal, the terms of the Proposal shall take precedence.</li>
                    <li>The Services provided by Geo Chem under this Agreement and any memoranda, laboratory
                        data, calculations, measurements, estimates notes, certificates and other material prepared by Geo Chem in the course of providing the Services to the Client, together with status summaries or any other communication in any form
                        describing the results of any work or services performed (Report(s)) shall be only for the Client's use and benefit.</li>
                    <li>The Client acknowledges and agrees that if in providing the Services Geo Chem is obliged to deliver a Report to a third party, Geo Chem shall be deemed irrevocably authorized to deliver such Report to the applicable third party. For
                        the purposes of this clause an obligation shall arise on the instructions of the Client, or where, in the reasonable opinion of Geo Chem, it is implicit from the circumstances, trade, custom, usage or practice.</li>
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
                    <li>The Client shall pay Geo Chem the charges set out in the Proposal, if applicable, or as otherwise contemplated for provision of the Services (the Charges).</li>
                    <li>The Charges are expressed exclusive of any applicable taxes. The Client shall pay any applicable taxes on the Charges at the rate and in the manner prescribed by law, on the issue by Geo Chem of a valid invoice.</li>
                    <li>The Client agrees that it will reimburse Geo Chem for any expenses incurred by Geo Chem relating to the provision of the Services and is wholly responsible for any freight or customs clearance fees relating to any testing samples.</li>
                    <li>The Charges represent the total fees to be paid by the Client for the Services pursuant to this Agreement. Any additional work performed by Geo Chem will be charged on a time and material basis.</li>
                    <li><b>Geo Chem</b> shall invoice the Client for the Charges and expenses, if any. The Client shall pay each invoice within thirty (30) days of receiving it.</li>
                    <li>If any invoice is not paid on the due date for payment, Geo Chem shall have the right to charge, and the Client shall pay, interest on the unpaid amount, calculated from the due date of the invoice to the date of receipt of the amount in
                        full at a rate equivalent to 3% per cent per annum above the base rate.</li>
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
                                of any such claims relating to any one Service exceeds the limit of liability set out in Clause 10 above;</li>
                            <li>Any claims results arising as result of any misuse run authorized use of any Reports issued by Geo Chem or any Intellectual Property Rights belonging to Geo Chem (including trade marks) pursuant to this Agreement; and</li>
                            <li>Any claims arising out of or relating to any third party's use of or reliance on any Reports or any reports, analyses, conclusions of the Client (or any third party to whom the Client has provided the Reports) based in whole or in part on
                                the Reports, if applicable.</li>
                        </ol>

                    </li>
                    <li>The obligations set out in this Clause 11 shall survive termination of this Agreement.</li>
                </ol>
            </li>
            <li><b>TERMINATION</b>
                <ol>
                    <li>This Agreement shall commence upon the first day on which the Services are commenced and shall continue, unless terminated earlier in accordance with this Clause 13, until the Services have been provided.</li>
                    <li>This Agreement may be terminated by:
                        <ol>
                            <li>Either party if the other continues in material breach of any obligation imposed up on it here under for more than thirty (30) days after written notice has been dispatched by that Party by recorded delivery or courier requesting the
                                other to remedy such breach;</li>
                            <li>Geo Chem on written notice to the Client in the event that the Client fails to pay any invoice by its due date and/or fails to make payment after a further request for payment; or</li>
                            <li>Either party on writ ten notice to the other in the event that the other makes any voluntary arrangement with its creditors or becomes subject to an administration order or (being an individual or firm) becomes bankrupt or (being a
                                company) goes into liquidation (otherwise than for the purposes of a solvent amal gamation or reconstruction) or an encumbrance takes possession, or a receiver is appointed, of any of the property or assets of the other or the other
                                ceases, or threatens to cease, to carry on business.</li>
                        </ol>
                    </li>
                    <li>In the event of termination of the Agreement for any reason and without prejudice to any other rights or remedies the parties may have, the Client shall pay Geo Chem for all Services performed up to the date of termination. This
                        o b l i g a t i o n s h a l l s u r v i v e termination or expiration of this Agreement.</li>
                    <li>Any termination or expiration of the Agreement shall not affect the accrued rights and obligations of the parties nor shall it affect any provision which is expressly or by implication intended to come in to force or continue in force on or
                        after such termination or expiration.</li>
                </ol>
            </li>
            <li><b>ASSIGNMENTAND SUB-CONTRACTING</b>
                <ol>
                    <li>G e o C h e m reserves the right to delegate the performance of its obligations here under and the provision of the Services to one or more of its affiliates and/or sub-contractors when necessary. G e o C h e m may also assign this
                        Agreement to any company within the G e o C h e m group on notice to the Client.</li>
                </ol>
            </li>
            <li><b>MISCELLANEOUS</b><br>
                <b>Severability</b>

                <ol>
                    <li>If any provision of this Agreement is or becomes invalid, illegal or unenforceable, such provision shall be severed and the remainder of the provisions shall continue in full force and effect as if this Agreement had been executed
                        without the invalid illegal or unenforceable provision. If the in validity, illegality or unenforceability is so fundamental that it prevents the accomplishment of the purpose of this Agreement, Geo Chem and the Client shall immediately
                        commence good faith negotiations to agree an alternative arrangement. <br>
                        <b>No partnership or agency</b>
                    </li>
                    <li>Nothing in this Agreement and no action taken by the parties under this Agreement shall constitute a partnership, association, joint venture or other co-operative entity between the parties or constitute any party the partner, agent or
                        legal representative of the other. <b>Waivers</b></li>
                    <li>T he failure of any party to insist upon strict performance of any provision of this Agreement, or to exercise any right or remedy to which it is entitled, shall not constitute a waiver and shall not cause a diminution of the obligations
                        established by this Agreement. A waiver of any breach shall not constitute a waiver of any subsequent breach.</li>
                    <li>No waiver of any right or remedy under this Agreement shall be effective unless it is expressly stated to be a waiver and communicated to the other party in writing. <br> <b>Whole Agreement</b></li>
                    <li>This Agreement and the Proposal contain the whole agreement between the parties relating to the transactions contemplated by this agreement and supersedes all previous agreements, arrangements and understandings between
                        the parties relating to those transactions or that subject matter. No purchase order, statement or other similar document will add to or vary the terms of this Agreement.</li>
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
                    <li>No amendment to this Agreement shall be effective unless it is in writing, expressly stated to amend this Agreement and signed by an authorized signatory of each party.</li>
                </ol>
            </li>
            <li><b>GOVERNING LAW</b>
                <ol>
                    <li>The Agreement and the rights and obligation of the parties shall in all respects be governed, constructed, interpreted and operated with Indian Law.
                        These General Conditions shall be Governed and construed in accordance with the substantive laws of the place where the Company renders services and issues reports or certificates, exclusive of any rules with respect of conflicts of
                        laws. All Disputes arising in connection with these General Conditions shall be finally settled by recourse to arbitration under the rules of conciliation and arbitration of the International Chamber of Commerce by one or more arbitrators
                        appointed in accordance with the said rules. Unless otherwise agreed, the arbitration shall take place in the English language at the place where the Company renders services and issues reports or certificates.
                        the said rules. Unless otherwise agreed, the arbitration shall take place in the English language at the place where the Company renders services and issues reports or certificates.</li>
                </ol>
            </li>
        </ol>

        <h3 style="text-align: center;">Regd. Off.: Geo-Chem House, 294, Shahid Bhagat Singh Road, Fort, Mumbai 400001. India.</h3>
        <h3 style="text-align: center;"><a href="www.basilrl.com">www.basilrl.com</a></h3>

    </div>

</body>

</html>