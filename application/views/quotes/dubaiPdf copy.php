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

        @page {

            margin-top: 3.0cm !important;
            margin-bottom: 2cm !important;
            background-position: top center;
            header: html_myHeader1;
            footer: html_myFooter;

        }

        @page noheader {
            /* background: url('https://cpslims-prod.s3.ap-south-1.amazonaws.com/format/quote_pdf_background.png') no-repeat 0 0; */
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
        <table style="width:100%; text-align:left;">

            <tr>
                <td>
                    <img width="160px" height="50px" src="<?php echo base_url('public/img/logo/logo-login.png') ?>" alt="">
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
        <div style="text-align: center;">

            <h3>GEO-CHEM MIDDLE EAST</h3>
            <p>Plot No. 18-0, Dubai Investment Park II, Affection Plan # 597-22, EBC Warehouse # 6. Dubai. UAE.</p>
            <p>E-mail:<a href="mailto:cps.dubai@basilrl.com">cps.dubai@basilrl.com</a>Web: <a href="mailto:http://www.basilrl.com">http://www.basilrl.com</a></p>
            <span style="float: right;width:100%;">Pages {PAGENO} of {nb}</span>
        </div>

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
        <?php $total_value = 0; 
           
            if(array_key_exists('currency_code',$data)){
                $currency_code = $data->currency_code;
            }  
            else{
                $currency_code = '';
            }  
        
        ?>
     
        <!-- test table details -->


        <?php if (!empty($test_data[0]->id)) : ?>
            <?php if ($test_data && count($test_data) > 0) { ?>

                <h3 style="text-align: center;">TEST DETAILS :</h3>
                <table class="test_table" style="margin-top:0px;">
                    <thead>
                        <tr>
                            <th>Sr. No.</th>
                            <th>Product Name</th>
                            <th>Division Name</th>
                            <th>Test Name</th>
                            <?php if (array_key_exists('show_test_method', $data)) {
                                if ($data->show_test_method == '1') { ?>
                                   <th>Test Method</th>
                            <?php }
                            } ?>
                            
                            <?php if (array_key_exists('show_discount', $data)) {
                                if ($data->show_discount == '1') { ?>
                                    <th>Test Price</th>
                                    <th>Discount</th>

                            <?php }
                            } ?>

                            <th>Geo Chem Discounted Price Proposal (<?php echo $currency_code;?>)</th>
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

                                <?php if (array_key_exists('show_test_method', $data)) {
                                if ($data->show_test_method == '1') { ?>
                                  <td><?php echo ($value->test_method) ? ($value->test_method) : "" ?></td>


                            <?php }
                            } ?>
                                

                                <?php if (array_key_exists('show_discount', $data)) {
                                    if ($data->show_discount == '1') { ?>
                                        <td><?php echo ($value->price) ? ($value->price) : 0.000 ?></td>
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

            <h3 style="text-align: center;">PROTOCOL DETAILS :</h3>
            <h3 style="text-align: center;"><?php echo ($protocol_data['product_name']) ? $protocol_data['product_name'] : ""; ?></h3>

            <table class="test_table" style="margin-top:0px;">
                <thead>
                    <tr>
                        <th>Sl No</th>
                        <th>Protocol Name</th>
                        <th>Test</th>
                        <th>Protocol Cost (<?php echo $currency_code;?>)</th>

                        <?php if (array_key_exists('show_discount', $data)) {
                            if ($data->show_discount) { ?>
                                <th>Discount</th>

                        <?php }
                        } ?>
                        <th>Geo Chem Price (<?php echo $currency_code;?>)</th>
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
                        <th>Package Cost (<?php echo $currency_code;?>)</th>

                        <?php if (array_key_exists('show_discount', $data)) {
                            if ($data->show_discount) { ?>
                                <th>Discount</th>

                        <?php }
                        } ?>
                        <th>Geo Chem Price (<?php echo $currency_code;?>)</th>
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


        <?php $vat = round(($total_value / 100) * 5); ?>


        <br><br>
        <table style="width: 100%;">

            <?php if($data->gst && count($data->gst)>0):?>
                <?php echo $data->gst['TAX'];?>
            <?php endif;?>
        </table>

        <table>
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
            <p><b>Payments due within 30 days of invoice. Please mention Invoice No. while effecting remittance.</b></p>
            <p><b>Banker's Details: Habib Bank AG Zurich, Al Falah St. Branch, P.O. Box 2681, Abu Dhabi - United Arab Emirates</b></p>
            <p><b>USD A/C No. 02030720311333874858, IBAN CODE: AE440290720311333874858</b></p>
            <p><b>AED A/C No. 02030720311105875462, IBAN CODE: AE830290720311105875462</b></p>
            <p><b>EURO A/C No. 02030720311974874858, IBAN CODE: AE040290720311974874858</b></p>
            <p><b>JPY A/C No. 02030720311534874858, IBAN CODE: AE820290720311534874858</b></p>
            <p><b>Swift Code - HBZUAEADXXX</b></p>
            <p><b>Correspondence Bank In New York: Habib American Bank, Swift Code: HANYUS33</b></p>

            <p>Thanks and Regards,</p>
            <p>Jocelyn Teodoro</p>
            <p>DUBAI</p>
            <p>Tel : </p>
            <p>Email : <a href="mailto:Jocelyn.teodoro@basilrl.com">Jocelyn.teodoro@basilrl.com</a></p>
        </div>

    </div>

    <div style="page-break-inside: auto !important;" class="chapter2 noheader">
        <h3 style="text-align: center;font-size:medium;color:#9B9369">GEO-CHEM'S GENERAL CONDITIONS FOR INSPECTION AND TESTING SERVICES</h3>

        <columns column-count="2" vAlign="justify">
            <ol style="color:#B9AE73;font-size:6.35pt;text-align:justify;line-height:0%;padding:0%;margin:0%">
                <li>Unless otherwise specifically agreed in writing the Company (Geo Chem Middle East)
                    undertakes services in accordance with these general conditions and accordingly all
                    offers to tenders of service are made subject to the same. All resulting contracts,
                    agreements or other arrangements will in alt respects be governed by these conditions,
                    except only to the extent that the law of the place where such arrangements of
                    contracts are made or carried out shall preclude any of the conditions and in such case
                    the said local law shall prevail wherever, but only to the extent that, it is at variance
                    with these conditions.</li>

                <li>The company is a business enterprise engaged in the trade of inspection and
                    testing. As such, it: - carries out inspections, verifications, examinations, tests,
                    sampling, measurements and similar operations; - issue reports and certificates
                    relating to the aforesaid operations; - renders advisory services in connection with
                    such matters.</li>

                <li>The company acts for the persons or bodies from whom the instructions to act have
                    originated (hereinafter called the "Client"). No other party is entitled to give
                    instructions. Particularly on the scope of inspection / testing or delivery of report or
                    certificate, unless so authorized by the client. The Company will however be deemed
                    irrevocable authorized by the Client to deliver at its discretion of report or certificate
                    to a third party where so instructed by the Client, if a promise in this sense had been
                    given to this third party or such a promise implicitly follows from circumstances, trade
                    custom, usage or practice.</li>

                <li>The company will provide services in accordance with: - the Client's specific
                    instructions as confirmed by the company - the terms of the Company's standard
                    order form and / or standard specification sheet, if used. - any relevant trade
                    custom, usage or practice; - methods as the Company shall consider suitable on
                    technical, operational and / or financial grounds.</li>

                <li>Documents reflecting engagements contracted between the Client and third parties
                    such as copies of contracts of sales, letters of credit , bills of lading, etc. are (if
                    received by the Company) considered to be for information only, without extending
                    or restricting the Company's mission, obligations and scope of services.</li>

                <li>The Company's standard services are as follows: - quantities and / or qualitative
                    inspection; - inspection of condition of goods, packing, containers and
                    transportation; - inspection of loading or discharging; - sampling; laboratory
                    analysis or other testing.</li>

                <li>Special services where the same exceed the scope of standard services as referred to
                    in paragraph 6, will only be undertaken by the company by particular arrangements.
                    Such special services will illustratively not exhaustively: - quantities andlor qualitative
                    inspection; - grouped services including concurrent and consequent operations; -
                    supervision of full industrial project schemes, including consultanting, expediting and
                    progress reporting.</li>

                <li>Subject to the Client's instructions, as accepted by the Company, the Company will
                    issue reports and certificates of inspection/testing which reflect statements of
                    opinions made with due care within the limitation of instructions received, but the
                    Company is under no obligation to refer to report upon any facts or circumstances
                    which are outside the specific instructions received.</li>

                <li>The Client will: -ensure that instructions to the Company and sufficient information
                    are given in due time to enable the required services to-be performed effectively. -
                    procure all necessary access for the Company's representatives to goods, premises
                    installations and transport in order to -enable the required services to be performed
                    effectively. -supply, if required, any special instrument / equipment and personnel
                    necessary for the performance of the required -services; -ensure that all necessary
                    measures are taken for safety and security of working conditions, sites and
                    installations during the -performance of services and will not rely, in this respect, on
                    the Company's advise whether required or not; -take all necessary steps to eliminate
                    or remedy any obstruction to, or interruptions in, the performance of the required -
                    services; -inform the company in advance of any known hazards or dangers, actual or
                    potential, associated with any order or -samples or testing including, for example,
                    presence or risk or radiation, toxic or noxious or explosive elements or -materials,
                    environmental pollution or poisons; -fully exercise all its rights and discharge all its
                    liabilities under any relevant contract of sale or any other contract with a -third
                    party, whether or not a report or certificate has been issued by the Company, failing
                    which the Company shall be -under no obligation to the client.</li>

                <li>The Company may delegate the performance of the whole or any part of the
                    services contracted for with the Client to any agent or subcontractor.</li>

                <li>1f the requirements of the Client necessitate the analysis of samples by the Client's
                    laboratory or by any third party's laboratory the Company will pass on the result of
                    the analysis but without responsibility for its accuracy. Likewise where the Company
                    is only able to witness an analysis by the Client's laboratory or by any third party's
                    laboratory the Company will provide confirmation. that the correct sample has been analyzed but will not otherwise be responsible for the accuracy of any analysis or results.</li>

                <li>The Company undertakes to exercise due care and skill in the performance of it's
                    services and accepts responsibility only in cases of proven negligence. The liability
                    of the Company to the Client in respect of any claims for loss, damage or expense
                    of whatsoever nature and howsoever arising shall in no circumstances exceed a
                    total aggregate sum equal to 5 times the amount of the fee or commission
                    payable in respect of the specific service required under the particular contract
                    which gives rise to such claims, provided, however, that the Company shall have
                    no liability for any indirect, special or consequential loss including loss of profits.</li>

                <li>Where the fee or commission payable relates to a number of services and a
                    claim arises in respect of one of those services, the fee or commission shall be
                    apportioned for the purposes of this paragraph by reference to the estimated
                    time involved in the performance of each service.</li>

                <li>The Company shall be discharged from all liability for all claims for loss, damage
                    or expense unless suit is brought within three months after the date of the
                    performance by the Company of the specific service which gives rise to the claim
                    or in the event of any alleged non-performance within one year of the date when
                    such service should have been completed.</li>

                <li>The Client acknowledges that the Company does not, either by entering into a
                    contract or by performing services, assume, abridge, abrogate or undertake to
                    discharge any duty of the Client to any other person.</li>

                <li>The Company is neither an insurer nor a guarantor and disclaims all liability in
                    such capacity. Clients seeking a guarantee against loss or damage should obtain
                    appropriate insurance. The Client shall guarantee, hold harmless and indemnify
                    the Company and its directors, employees, servants, officers, agents or
                    subcontractors against all claims made by any third party for loss, damage or
                    expense of whatsoever nature including reasonable legal expenses and
                    howsoever arising relating to the performance, purported performance or nonperformance,
                    of any services to the extent that the aggregate of any such claims
                    relating to any one service exceed the limit mentioned in paragraph 12.</li>

                <li>1n the event that any unforeseen problem or expenditure arises in the course of
                    carrying out any of the services, the Company shall be entitled to an additional
                    charge to cover additional time and cost necessarily incurred to complete the
                    services.</li>

                <li>1f the Company is unable to perform all or part of the services because of lack of
                    access or availability of goods or undue postponement or delay, the Company
                    shall be entitled to delay charge and to reimbursement of any non-refundable
                    expense incurred by the Company.</li>

                <li>The Client shall punctually pay not later than 30 days after the relevant invoice
                    date or within such other period as may have been agreed in writing by the
                    Company, all charges rendered by the Company failing which interest will
                    become due at the rate of 12 percent per annum from the date of invoice until
                    payment.</li>

                <li>The Client shall not be entitled to retain or defer payment of any sums due to the
                    Company on account of dispute, cross claim or set off which it may allege against
                    the Company. The Client shall also pay all of the Company's costs of collecting
                    any amounts owed to the Company, including attorney's fees and court costs.</li>

                <li>In the event of any suspension of payment, arrangement with creditors,
                    bankruptcy, insolvency, receivership or cessation of business by the Client, the
                    Company shall be entitled to suspend or, at its option, terminate all further
                    services forthwith and without liability. In the event of the Company being
                    prevented by reasons of any cause whatsoever outside the Company's control
                    from performing or completing any service for which an order has been given or
                    agreement made, the Client will pay the Company: - the amount of all abortive
                    expenditure actually made or incurred; - a proportion of the agreed fee or
                    commission equal to the proportion (if any) of the services actually carried out;
                    and the Company shall be relieved of all responsibility whatsoever for the partial
                    or total non-performance of the required service.</li>

                <li>These General Conditions shall be Governed and construed in accordance with
                    the substantive laws of the place where the Company renders services and
                    issues reports or certificates, exclusive of any rules with respect of conflicts of
                    laws. All Disputes arising in connection with these General Conditions shall be
                    finally settled by recourse to arbitration under the rules of conciliation and
                    arbitration of the international Chamber of Commerce by one or more
                    arbitrators appointed in accordance with the said rules. Unless otherwise
                    agreed, the arbitration shall take place in the English language at the place
                    where the Company renders services and issues reports or certificates</li>


            </ol>


        </columns>






</body>

</html>