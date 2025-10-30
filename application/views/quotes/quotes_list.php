<style>
    /* ---------- */
    .customer_list {
        width: 90%;
    }

    .switch {
        position: relative;
        display: inline-block;
        width: 30px;
        height: 15px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 15px;
        width: 15px;
        left: 0px;
        bottom: 0px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
    }

    input:checked+.slider {
        background-color: #2196F3;
    }

    input:focus+.slider {
        box-shadow: 0 0 1px #2196F3;
    }

    input:checked+.slider:before {
        -webkit-transform: translateX(15px);
        -ms-transform: translateX(15px);
        transform: translateX(15px);
    }

    /* Rounded sliders */
    .slider.round {
        border-radius: 34px;
    }

    .slider.round:before {
        border-radius: 50%;
    }
</style>
<script src="<?php echo base_url('ckeditor/ckeditor.js'); ?>"></script>
<div class="content-wrapper">
    <section class="content-header">
        <!-- container fluid start -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1>QUOTES</h1>
                </div>
            </div>

            <hr>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">

                            <div class="row">
                                <div class="col-sm-6">
                                    <?php if (exist_val('Quotes/add_quote', $this->session->userdata('permission'))) { ?>
                                        <a class="btn btn-sm btn-primary add" href="<?php echo base_url('quotes_form') ?>">ADD</a>

                                    <?php } ?>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-sm-3">
                                    <input class="created_by_id" type="hidden" value="<?php echo ($created_by_id) ? $created_by_id : '' ?>" name="created_by">
                                    <input class="form-control form-control-sm  input-sm created_by" value="<?php echo ($created_by) ? $created_by->created_by : ''; ?>" autocomplete="off" name="created_by" type="text" placeholder="Created by">
                                    <ul class="list-group-item created_by_list" style="display:none">
                                    </ul>
                                </div>

                                <div class="col-sm-1">
                                    <label for="">START DATE</label>
                                </div>

                                <div class="col-sm-3">
                                    <input type="date" name="from_date" class="form-control form-control-sm from_date" placeholder="TO DATE" value="<?php echo ($from_date) ? $from_date : '' ?>">
                                </div>

                                <div class="col-sm-1">
                                    <label for="">END DATE</label>
                                </div>
                                <div class="col-sm-3">
                                    <input type="date" name="to_date" class="form-control form-control-sm to_date" placeholder="END DATE" value="<?php echo ($to_date) ? $to_date : '' ?>">
                                </div>

                            </div>
                            <br>

                            <div class="row">
                                <div class="col-sm-3">
                                    <input class="customer_id" type="hidden" value="<?php echo ($customer_id) ? $customer_id : '' ?>" name="customer_id">
                                    <input class="form-control form-control-sm  input-sm customer_name" value="<?php echo ($customer_name) ? $customer_name->customer_name : ''; ?>" autocomplete="off" name="customer_name" type="text" placeholder="Type Customer Name">
                                    <ul class="list-group-item customer_list" style="display:none">
                                    </ul>
                                </div>

                                <div class="col-sm-3">
                                    <select name="" value="<?php echo ($quote_status) ? $quote_status : ""; ?>" class="form-control form-control-sm quote_status">

                                    </select>
                                </div>
                                <div class="col-sm-4">
                                    <input value="<?php echo ($search) ? $search : ""; ?>" id="search" class="form-control form-control-sm" type="text" placeholder="Search" aria-label="Search">
                                </div>

                                <div class="col-sm-2">
                                    <button onclick="searchfilter();" type="button" class="btn btn-sm btn-default" title="Search"><img src="<?php echo base_url('assets/images/search.png') ?>" alt=""></button>
                                    <a class="btn btn-sm btn-primary" href="<?php echo base_url('quotes'); ?>">CLEAR</a>
                                </div>
                                <!-- <div class="col-sm-1">
                                    <a class="btn btn-sm btn-default" title="Excel Export" href="<?php echo base_url('Quotes/excel_export') ?>"><img src="<?php echo base_url('assets/images/imp_excel.png') ?>" alt="export excel"></a>
                                </div> -->
                            </div>
                        </div>
                        <!-- end card header -->

                        <div class="table-responsive p-2">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <?php
                                        if ($search) {
                                            $search = base64_encode($search);
                                        } else {
                                            $search = "NULL";
                                        }
                                        if ($customer_id != "") {
                                            $customer_id = $customer_id;
                                        } else {
                                            $customer_id = "NULL";
                                        }
                                        if ($quote_status != "") {
                                            $quote_status = $quote_status;
                                        } else {
                                            $quote_status = "NULL";
                                        }
                                        if ($order != "") {
                                            $order = $order;
                                        } else {
                                            $order = "NULL";
                                        }
                                        if ($from_date != "") {
                                            $from_date = $from_date;
                                        } else {
                                            $from_date = "NULL";
                                        }
                                        if ($to_date != "") {
                                            $to_date = $to_date;
                                        } else {
                                            $to_date = "NULL";
                                        }

                                        if ($created_by_id != "") {
                                            $created_by_id = $created_by_id;
                                        } else {
                                            $created_by_id = "NULL";
                                        }

                                        ?>
                                        <th scope="col">S. NO.</th>
                                        <th scope="col"><a href="<?php echo base_url('Quotes/index/' . $customer_id . '/' . $quote_status .  '/' . $created_by_id . '/' . $from_date . '/' . $to_date . '/' . $search . '/' . base64_encode('cus.customer_name') . '/' . $order) ?>">CUSTOMER</a></th>
                                        <th scope="col"><a href="<?php echo base_url('Quotes/index/' . $customer_id . '/' . $quote_status . '/' . $created_by_id . '/' . $from_date . '/' . $to_date . '/' . $search . '/' . base64_encode('qt.reference_no') . '/' . $order) ?>">QUOTE REF NO.</a></th>
                                        <th scope="col"><a href="<?php echo base_url('Quotes/index/' . $customer_id . '/' . $quote_status . '/' . $created_by_id . '/' . $from_date . '/' . $to_date . '/' . $search . '/' . base64_encode('qt.quote_date') . '/' . $order) ?>">QUOTE DATE</a></th>
                                        <th scope="col"><a href="<?php echo base_url('Quotes/index/' . $customer_id . '/' . $quote_status . '/' . $created_by_id . '/' . $from_date . '/' . $to_date . '/' . $search . '/' . base64_encode('qt.quote_value') . '/' . $order) ?>">QUOTE VALUE</a></th>
                                        <th scope="col">QUOTE STATUS</th>
                                        <th scope="col"><a href="<?php echo base_url('Quotes/index/' . $customer_id . '/' . $quote_status . '/' . $created_by_id . '/' . $from_date . '/' . $to_date . '/' . $search . '/' . base64_encode('qt.created_by') . '/' . $order) ?>">CREATED BY</a></th>
                                        <th scope="col"><a href="<?php echo base_url('Quotes/index/' . $customer_id . '/' . $quote_status . '/' . $created_by_id . '/' . $from_date . '/' . $to_date . '/' . $search . '/' . base64_encode('qt.created_on') . '/' . $order) ?>">CREATED ON</a></th>
                                        <th scope="col">ACTION</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    <?php $sn = $this->uri->segment('11') + 1;
                                    if ($quotes_list) {
                                        //   $quotes_list = $quotes_list[0];
                                        foreach ($quotes_list as $key => $item) { ?>
                                            <tr>
                                                <td><?php echo $sn; ?></td>
                                                <td><?php echo $item->customer_name ?></td>
                                                <td><?php echo $item->reference_no ?></td>
                                                <td><?php echo $item->quote_date ?></td>
                                                <td><?php echo $item->quote_value ?></td>
                                                <td><?php echo $item->quote_status ?></td>
                                                <td><?php echo $item->created_by ?></td>
                                                <td><?php echo $item->created_on ?></td>
                                                <td>



                                                    <?php if (exist_val('Quotes/generate_quote', $this->session->userdata('permission'))) { ?>
                                                        <?php if ($item->quote_status == "Draft") { ?>
                                                            <button type="button" data-id="<?php echo $item->quote_id ?>" data-branch="<?php echo $item->quotes_branch_id ?>" style="cursor:pointer;" data-bs-toggle="modal" data-bs-target="#preview_quotes" class="gen_quotes_btn btn btn-sm btn-default" title="Preview Quote">
                                                                <img alt="generate quote" src="<?php echo base_url('assets/images/Generate quote.png') ?>"></button>

                                                            <a href="<?php echo base_url('Quotes/edit_quote') . '/' . base64_encode($item->quote_id) ?>" class="btn btn-sm btn-default" title="Edit Quote"><img alt="Edit quote" title="Edit Quote" src="<?php echo base_url('assets/images/mem_edit.png') ?>"></a>
                                                        <?php } ?>
                                                    <?php } ?>

                                                    <?php if ($item->quote_status == "Approval Pending") { ?>
                                                        <a data-id="<?php echo $item->quote_id ?>" style="cursor:pointer;" data-bs-toggle="modal" data-bs-target=".revert_quote_popup" class="revert_quotes_btn">
                                                            <img alt="generate pdf" title="Generate PDF" src="<?php echo base_url('assets/images/revert.png') ?>"></a>



                                                    <?php } ?>

                                                    <?php if (exist_val('Quotes/approve_quotes', $this->session->userdata('permission'))) { ?>

                                                        <?php if ($item->quote_status == "Awaiting Approval") { ?>
                                                            <a data-id="<?php echo $item->quote_id ?>" style="cursor:pointer;" data-bs-toggle="modal" data-bs-target=".approve_pdf" class="btn btn-sm btn-default approve_quotes_btn" title="View Quote"><img height="20px" width="20px" alt="approve" src="<?php echo base_url('assets/images/icon/view-report.png') ?>"></a>

                                                            <a href="<?php echo base_url('Quotes/edit_quote') . '/' . base64_encode($item->quote_id) ?>" class="btn btn-sm btn-default" title="Edit Quote"><img alt="Edit quote" title="Edit Quote" src="<?php echo base_url('assets/images/mem_edit.png') ?>"></a>

                                                        <?php } ?>

                                                    <?php } ?>

                                                    <?php if ($item->quote_status == "Approved") { ?>
                                                        <a data-id="<?php echo $item->quote_id ?>" style="cursor:pointer;" data-bs-toggle="modal" data-bs-target="#update_customer_accept" class="btn btn-sm btn-default update_customer_acceptance" title="Accepted By Client"><img height="20px" width="20px" alt="approve" src="<?php echo base_url('assets/images/icon/approval.png') ?>"></a>

                                                    <?php } ?>

                                                    <?php if (exist_val('Quotes/GeneratePDF_quotes', $this->session->userdata('permission'))) { ?>

                                                        <?php if ($item->quote_status == "Approved" || $item->quote_status == "Cps Approved" || $item->quote_status == 'Client Approved') { ?>
                                                            <?php if (!empty($item->approve_pdf_path)) { ?>
                                                                <a href="<?php echo base_url('Quotes/download_quotePDF/' . base64_encode($item->approve_pdf_path)); ?>" style="cursor:pointer;" class="pdf_quotes_btn btn btn-sm btn-default" title="Download PDF">
                                                                    <img alt="PDF" src="<?php echo base_url('assets/images/downloadpdf.png') ?>"></a>

                                                                <?php //if ($item->trf_id == '') { ?>
                                                                    <a href="<?php echo base_url('Quotes/revise_quote') . '/' . base64_encode($item->quote_id) ?>" class="btn btn-sm btn-default" title="Revise Quote"><img alt="Revise quote" src="<?php echo base_url('assets/images/revise-quote.png') ?>"></a>

                                                                <?php } ?>
                                                            <?php //} else { ?>

                                                                <a data-id="<?php echo $item->quote_id ?>" style="cursor:pointer;" data-bs-toggle="modal" data-bs-target=".approve_pdf" class="approve_quotes_btn">
                                                                    <img alt="approve" title="View Quotation" src="<?php echo base_url('assets/images/approved_jobs.png') ?>"></a>

                                                            <?php //} ?>

                                                        <?php } ?>
                                                    <?php } ?>

                                                    <a href="javascript:void(0)" title="Clone Quotation" class="btn btn-default clone_quote" data-quote_id="<?php echo $item->quote_id; ?>"><i class="fas fa-clone"></i></a>
                                                    <button type="button" class="btn btn-sm btn-default user_log_btn" title="User Log" data-id="<?php echo $item->quote_id ?>" data-bs-toggle="modal" data-bs-target=".user_log_windows">
                                                        <img src="<?php echo base_url('assets/images/log-view.png') ?>" alt="USER LOG">
                                                    </button>


                                                </td>
                                            </tr>
                                    <?php $sn++;
                                        }
                                    } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- card end -->
                </div>
            </div>

            <!-- menu end -->

            <div class="card-header">

                <?php if ($quotes_list && count($quotes_list) > 0) { ?>
                    <span><?php echo $links ?></span>
                    <span><?php echo $result_count; ?></span>
                <?php } else { ?>
                    <h3>NO RECORD FOUND</h3>
                <?php } ?>

            </div>
        </div>
        <!-- container fluid end -->
    </section>
</div>

<!-- Modal to preview Quotation -->
<div class="modal fade" id="preview_quotes" tabindex="-1" role="dialog" aria-labelledby="preview_quotesLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="preview_quotesLabel">Preview Quote</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="<?php echo base_url('Quotes/generate_quote') ?>" id="generate_quote">
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                <div class="modal-body">
                    <input type="hidden" name="quote_id" class="quote_id">
                    <div id="pdf_view"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Send Email For Approval</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-lg generate_quote_popup " tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg ">
        <div class="modal-content modal-xl vertical-align-center " style="margin: 0 auto;">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Generate Quote</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="generate_quotes" action="javascript:void(0);">
                <div class="modal-body">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">


                    <input type="hidden" name="quote_id" class="quote_id" id="" value="">

                    <div class="row">
                        <div class="col-sm-3">
                            <label for="">SHOW PRICE ?</label>
                            <select name="show_price" id="" class="form-control form-control-sm">
                                <option selected disabled>SELECT</option>
                                <option value="1">YES</option>
                                <option value="0">NO</option>
                            </select>
                        </div>

                        <div class="col-sm-3">
                            <label for="">SHOW DISCOUNT ?</label>
                            <select name="show_discount" id="show_discount" class="form-control form-control-sm">
                                <option selected disabled>SELECT</option>
                                <option value="1">YES</option>
                                <option value="0">NO</option>
                            </select>
                        </div>

                        <div class="col-sm-3">
                            <label for="">SHOW TEST METHOD ?</label>
                            <select name="show_test_method" id="" class="form-control form-control-sm">
                                <option selected disabled>SELECT</option>
                                <option value="1">YES</option>
                                <option value="0">NO</option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <label for="">SHOW DIVISION ?</label>
                            <select name="show_division" id="" class="form-control form-control-sm">
                                <option selected disabled>SELECT</option>
                                <option value="1">YES</option>
                                <option value="0">NO</option>
                            </select>
                        </div>

                    </div>
                    <br>
                    <div class="row">
                        <div class="col-sm-3">
                            <label for="">SHOW TOTAL AMOUNT ?</label>
                            <select name="show_total_amount" id="" class="form-control form-control-sm">
                                <option selected disabled>SELECT</option>
                                <option value="1">YES</option>
                                <option value="0">NO</option>
                            </select>
                        </div>

                        <div class="col-sm-3">
                            <label for="">SHOW PRODUCTS ?</label>
                            <select name="show_products" id="" class="form-control form-control-sm">
                                <option selected disabled>SELECT</option>
                                <option value="1">YES</option>
                                <option value="0">NO</option>
                            </select>
                        </div>
                    </div>

                    <br>
                    <div class="row">
                        <div class="col-sm-12">
                            <label for="">INTRODUCTION </label>
                            <textarea class="ckeditor introduction" name="introduction">

                            </textarea>
                        </div>
                    </div>



                    <div class="gurugram_about_us_div">

                        <label for="">SHOW ABOUT US ?</label>
                        <select name="allow_about_us" id="" class="form-control form-control-sm">
                            <option selected disabled>SELECT</option>
                            <option value="1">YES</option>
                            <option value="0">NO</option>
                        </select>

                        <textarea class="ckeditor about_us_details" name="about_us_details">

                    </textarea>

                        <label for="">NOTES DETAILS</label>
                        <textarea name="notes_details" class="ckeditor notes_details">

                    <p><b><u><i>Notes - </i></u></b></p><ul class="notes_list"><li>For any test, which is not listed above will be offered with flat – 25% discount on our base price.</li><li>Price quoted above are excluding service tax.</li><li>Prices offered are based upon testing per component per material.</li><li>Composite testing will be performed upto maximum of three homogenous material together OR as per allowance under buyer’s manual.</li><li>Turn around time – 3 working days (excluding tests with increase turn around time).</li><li>Sample pick up facility at your door step</li><li>Credit Period – 30 days.</li></ul>
                    </textarea>

                        <!-- <label for="">TERMS & CONDITIONS</label>
                    <textarea name="terms_details" class="ckeditor terms_details" >

                    <div class="gredient" style="text-align: center; color:grey;font-size:30px">Terms and Conditions</div><ul class="term_conditions gredient" style="color: grey;"><li>Further this is also applicable for buyers testing with whom we don’t have any negotiated special agreement.</li><li>In case of buyer’s negotiated agreement testing, the mutually agreed (Geo-Chem and the Buyer) package rates shall be applicable.</li><li>GST shall be levied as per applicable govt. tax laws.</li><li>Above offer will be valid for 1 year</li></ul>
                    </textarea> -->

                        <label for="">CONTACT DETAILS</label>
                        <textarea class="ckeditor contact_details" name="contact_details">
                            <table style="border-collapse: collapse;" border="1px">
                                <thead>
                                    <tr>
                                        <th>Sr. No.</th>
                                        <th>Geo-Chem Contact Person</th>
                                        <th>Telephone</th>
                                        <th>Email</th>
                                        <th>Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>Mr. Vipin Balyan</td>
                                        <td>90170 37888</td>
                                        <td>vipin.b@basilrl.com</td>
                                        <td>For all Customer Services queries</td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>Mr. Nitin Jangra</td>
                                        <td>84478 19554</td>
                                        <td>Nitin.j@basilrl.com</td>
                                        <td>For all technical queries</td>
                                    </tr>
                                </tbody>
                            </table>
                        </textarea>



                    </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">NO</button>
                    <button type="submit" class="btn btn-primary generate_quote">YES</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- <div class="modal fade bd-example-modal-sm show_discount" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm ">
        <div class="modal-content modal-sm vertical-align-center " style="margin: 0 auto;">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Show Discount</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="show_discount_form" action="javascript:void(0);">
                <div class="modal-body">
                    <input type="hidden" name="">
                    <input type="hidden" name="quote_id" class="quote_id" id="" value="">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="show_discount" id="exampleRadios1" value="0" checked>
                        <label class="form-check-label" for="exampleRadios1">
                            YES
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="show_discount" id="exampleRadios2" value="1">
                        <label class="form-check-label" for="exampleRadios2">
                            NO
                        </label>
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-sm show_discount_submit">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div> -->


<div class="modal fade bd-example-modal-xl approve_pdf" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">ViewQuote</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="approve_quotes_form" action="javascript:void(0);">
                <div class="modal-body" style="height:100vh">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <input type="hidden" name="quote_id" class="quote_id" id="" value="">
                    <iframe class='preview_pdf' src="" frameborder="0" width="100%" height="100%"></iframe>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <div class="approver_btn_approval">

                    </div>

                </div>
            </form>
        </div>
    </div>
</div>



<div class="modal fade generatepdf_quote_popup" id="" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Quote Details</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid" style="height:60vh">
                    <iframe class="quote_pdf" src="" frameborder="0" height="100%" width="100%"></iframe>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="update_customer_accept" tabindex="-1" role="dialog" aria-labelledby="update_customer_acceptLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content modal-lg">
            <div class="modal-header">
                <h5 class="modal-title" id="update_customer_acceptLabel">Client Approval Status Update</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?php echo base_url('Quotes/update_customer_acceptance'); ?>" method="post" id="update_client_acceptance">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                            <input type="hidden" name="quote_id" class="quote_id" id="" value="">
                            <div class="form-group">
                                <label for="">Select Status</label>
                                <select name="approval_status" class="form-control form-control-sm">
                                    <option disabled="" selected>select status</option>
                                    <option value="Client Approved">Client Approved</option>
                                    <option value="Client Rejected">Client Rejected</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">Comment</label>
                                <textarea name="client_comment" class="form-control"></textarea>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-sm revert_quote_popup" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content  modal-sm" style="margin: 0 auto;">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Generate Quote</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="revert_quotes" action="javascript:void(0);">
                <div class="modal-body">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <input type="hidden" name="quote_id" class="quote_id" id="" value="">
                    <p>Are You Sure ?</p>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">NO</button>
                    <button type="submit" class="btn btn-primary  genpdf_quotes">YES</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- user log windows -->

<div class="modal user_log_windows" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="height:400px">
            <div class="modal-header">
                <h5 class="modal-title"><b>USER ACTION HISTORY</b></h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive">

                            <table class="table table-sm p-2 user_table">
                                <thead>
                                    <tr>
                                        <th scope="col">S. NO.</th>
                                        <th scope="col">ACTION MESSAGE</th>
                                        <th scope="col">DATE-TIME</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- end -->

<script>
    $(document).ready(function() {
        $('#update_client_acceptance').submit(function(e) {
            e.preventDefault();
            $('body').append('<div class="pageloader"></div>');
            var form = $(this);
            $.ajax({
                type: "post",
                url: form.attr('action'),
                data: form.serialize(),
                dataType: 'json',
                success: function(data) {
                    $('.pageloader').remove();
                    $('.errors_msg').remove();
                    if (data.status > 0) {
                        $.notify(data.message, "success");
                        window.setTimeout(function() {
                            window.location.replace("<?php echo base_url('Quotes/index'); ?>");
                        }, 2000);
                    } else {
                        $.notify(data.message, "error");
                    }
                    if (data.errors) {
                        $.each(data.errors, function(i, v) {
                            $('#update_client_acceptance *[name="' + i + '"]').after('<span class="text-danger errors_msg">' + v + '</span>');
                        });
                    }
                }
            });
        });
    });
</script>
<script>
    $('.update_customer_acceptance').click(function() {
        var quote_id = $(this).data('id');
        $('.quote_id').val(quote_id);
    });

    function searchfilter() {

        var url = '<?php echo base_url("Quotes/index"); ?>';


        var customer_name = $('.customer_name').val();

        if (customer_name) {
            var customer_id = $('.customer_id').val();
        } else {
            customer_id = '';
        }
        var quote_status = $('.quote_status').val();


        if (customer_id != '') {
            url = url + '/' + customer_id;
        } else {
            url = url + '/NULL';
        }
        if (quote_status != '') {
            url = url + '/' + btoa(quote_status);
        } else {
            url = url + '/NULL';
        }

        var created_by = $('.created_by').val();
        if (created_by) {
            var created_by_id = $('.created_by_id').val();
        } else {
            created_by_id = '';
        }
        if (created_by_id != '') {
            url = url + '/' + created_by_id;
        } else {
            url = url + '/NULL';
        }

        from_date = $('.from_date').val();
        if (from_date != '') {
            url = url + '/' + btoa(from_date);
        } else {
            url = url + '/NULL';
        }
        to_date = $('.to_date').val();
        if (to_date != '') {
            url = url + '/' + btoa(to_date);
        } else {
            url = url + '/NULL';
        }
        var search = $('#search').val();

        if (search != '') {
            url = url + '/' + btoa(search);
        } else {
            url = url + '/NULL';
        }


        window.location.href = url;

    }
</script>

<script>
    $(document).ready(function() {



        $('.from_date').on('change', function() {
            if ($('.to_date').val() == "") {

                $.notify('Please select end date!', 'error');
            }
        })

        $('.user_log_btn').on('click', function() {
            var quote_id = $(this).data("id");
            show_userLOg(quote_id);
        })


        function show_userLOg(quote_id) {
            const _tokken = $('meta[name="_tokken"]').attr("value");
            $.ajax({
                url: "<?php echo base_url('Quotes/show_user_log_history') ?>",
                method: "POST",
                data: {
                    quote_id: quote_id,
                    _tokken: _tokken
                },
                success: function(response) {
                    var data = $.parseJSON(response);
                    $('.user_table tbody').html("");
                    if (data) {
                        var serial_number = 1;
                        $.each(data, function(indexes, values) {
                            var row = "<tr>";
                            row += "<td>" + serial_number + "</td>";
                            row += "<td>" + values.msg + " " + values.user + "</td>";
                            row += "<td>" + values.date_time + "</td>";
                            row += "</tr>";
                            $('.user_table tbody').append(row);
                            serial_number++;
                        })
                    } else {
                        var row = "<tr>";
                        row += "<td colspan='3'>NO RECORD FOUND</td>";
                        row += "</tr>";
                        $('.user_table tbody').append(row);
                    }
                }
            });
            return false;
        }


        $('input').attr('autocomplete', 'off');

        $('.show_discount_switch').on('change', function() {
            if (this.checked) {
                $(this).attr('value', '1');
                $(this).next('span').attr('title', 'Hide Discount');
            } else {
                $(this).attr('value', '0');
                $(this).next('span').attr('title', 'Show Discount');
            }

            var show_discount = $(this).val();
            var quote_id = $(this).attr('data-id');
            update_discount_in_database(show_discount, quote_id);
        })

        function update_discount_in_database(show_discount, quote_id) {
            const _tokken = $('meta[name="_tokken"]').attr("value");
            $.ajax({
                url: "<?php echo base_url('Quotes/update_discount') ?>",
                method: "post",
                data: {
                    show_discount: show_discount,
                    quote_id: quote_id,
                    _tokken: _tokken
                },
                success: function(response) {
                    msg = $.parseJSON(response);
                    if (msg.status > 0) {
                        $.notify(msg.msg, 'success');
                    } else {
                        $.notify(msg.msg, 'error');
                    }
                }
            });
        }

        $('.customer_name').focus(function(e) {
            getAutolist('customer_id', 'customer_name', 'customer_list', 'customer_li', 'isactive="Active"', 'customer_name', 'customer_id as id,customer_name as name', 'cust_customers');

        })

        $('.created_by').focus(function(e) {
            getAutolist('created_by_id', 'created_by', 'created_by_list', 'created_by_li', '', 'admin_fname', 'uidnr_admin as id, CONCAT(admin_fname," ",admin_lname) as name', 'admin_profile');

        })
        // generate quotes

        $('#generate_quotes').submit(function(e) {

            e.preventDefault();
            var newData = new FormData(this);
            newData.append('about_us_details', CKEDITOR.instances['about_us_details'].getData());
            newData.append('notes_details', CKEDITOR.instances['notes_details'].getData());
            // newData.append('terms_details', CKEDITOR.instances['terms_details'].getData());
            newData.append('contact_details', CKEDITOR.instances['contact_details'].getData());
            newData.append('introduction', CKEDITOR.instances['introduction'].getData());
            $.ajax({
                url: "<?php echo base_url('Quotes/generate_quote') ?>",
                method: "post",
                data: newData,
                contentType: false,
                processData: false,
                success: function(data) {
                    var msg = $.parseJSON(data);
                    if (msg.status > 0) {
                        $.notify(msg.msg, 'success');
                        $('.revert_quote_popup').modal("hide");
                        location.reload();

                    } else {
                        $.notify(msg.msg, 'error');
                    }
                }
            })


        })


        // $('.gen_quotes_btn').on('click', function() {
        //     var branch = $(this).attr('data-branch');

        //     if(branch!='1'){
        //         $('.gurugram_about_us_div').css("display","none");
        //     }
        //     else{
        //         $('.gurugram_about_us_div').css("display","block");
        //     }
        //     var quote_id = $(this).attr("data-id");
        //     const _tokken = $('meta[name="_tokken"]').attr('value');
        //     $('.quote_id').val(quote_id);
        //     $.ajax({
        //             url:"<?php echo base_url('Quotes/generate_details') ?>",
        //             method:"post",
        //             data:{
        //                 _tokken:_tokken,
        //                 quote_id:quote_id
        //             },
        //             success:function(data){
        //                 result = $.parseJSON(data);
        //                 var version_number = "";
        //                 if (result.version_number != "") {
        //                     version = result.version_number;
        //                     if (version == 0) {
        //                         version_number = "";
        //                     } else if (version > 0) {
        //                         version_number = "_V." + version;
        //                     }
        //                 } else {
        //                     version_number = "";
        //                 }
        //                 var html_ck = '<div class="center_logo" style="text-align: center;"> <img width="800px" height="" src="https://cpslims-prod.s3.ap-south-1.amazonaws.com/format/quote_logo.png" alt="" style="padding: 120px;"><div style="font-size:55px; color: grey;font-weight:bold;font-family: Cambria, Cochin, Georgia, Times, "Times New Roman", serif;text-align:center; margin-top: 80px;">TEXTILES</div> <p style="font-size:25px; color: grey;font-weight:bold;font-family: Cambria, Cochin, Georgia, Times, "Times New Roman", serif;" >Quote Proposal For</p> <div style="background-color: #005ce6;color: white;height: auto;width: auto;padding: 10px;font-family: Arial, Helvetica, sans-serif !important;display:inline-block;font-size:30px;font-weight:bold">'+result.customer_name+'</div> <p style="color: grey;font-family: Cambria, Cochin, Georgia, Times, "Times New Roman", serif;font-size:20px;margin-top: 70px;">'+result.quote_date+'</p> </div> <div class="content" style="color: grey;font-weight:bold;font-family: Cambria, Cochin, Georgia, Times, "Times New Roman", serif;page-break-inside: auto !important;"> <h1>About Us</h1> <p style="font-size: 17px;line-height: 33px;letter-spacing: 1px;"> GEO-CHEM, founded in 1964, is an independent inspection and testing company with our regional headquarters in Dubai and branches across India, we are today one of the largest and reputable inspection and testing organizations in India. </p> <p style="font-size: 17px;line-height: 33px;letter-spacing: 1px;"> We are renowned as cargo inspectors and surveyors and have proven our expertise in Inspection, Survey and Testing of diverse export, import and locally traded cargos and commodities. An Independent, unbiased and quality driven inspection and testing company, Geo-Chem today has a strong reputation world- wide. </p> <p style="font-size: 17px;line-height: 33px;letter-spacing: 1px;"> Our services are available through a network of branch offices and associates, supported by an excellent infrastructure of ultramodern facilities, communication system and staff strength with vast experience in the industry. </p> <p style="font-size: 17px;line-height: 33px;letter-spacing: 1px;"> Through years of offering dedicated and professional services to our clients, we have built up enough confidence in the international trade to merit 100 per cent exclusive nominations from our clientele despite stiff competition. Geo-Chem&#39;s international client list is ample testimony to the confidence the company enjoys the world over with significantly low levels of complaints and claims, Geo- Chem focuses on client service, cost effective solutions and adheres to its time frame commitment. </p> <p style="font-size: 17px;line-height: 33px;letter-spacing: 1px;"><img src="https://cpslims-prod.s3.ap-south-1.amazonaws.com/format/logos.png" alt=""></p> <div style="page-break-inside: auto !important;"> <h1>Textiles Testing</h1> <p style="font-size: 17px;line-height: 33px;letter-spacing: 1px;">Geo Chem commences textile, fabric and garment testing from Yarn/ fabric samples up to the finished goods. We provide testing for the items including active wear, pyjamas, jeans, scarves, shirts, sweaters, outerwear, belts, valets bedding, curtains, towels, soft home furnishing, carpets, rugs, bathmats and others. We measure the attributes and performance comfort properties of any type of clothing in varied environmental conditions.</p> <h1>Testing services for apparel &amp; textile items:</h1> <ul class="test_list" style="font-size: 17px;line-height: 27px;letter-spacing: 1px;"> <li style="margin: 10px !important;">Fibre Composition analysis</li> <li style="margin: 10px !important;">Dimensional stability Test (shrinkage test)</li> <li style="margin: 10px !important;">Stain Resistance/ Stain release performance Test</li> <li style="margin: 10px !important;">Water absorbency/ Water repellence Test</li> <li style="margin: 10px !important;">pH test</li> <li style="margin: 10px !important;">Seam strength, Seam Slippage, Seam bursting Test</li> <li style="margin: 10px !important;">Fabric Weight / GSM Test (for knitted&amp; woven garments)</li> <li style="margin: 10px !important;">Antibacterial finish durability Test</li> <li style="margin: 10px !important;">Extractable Heavy metals</li> <li style="margin: 10px !important;">Breathability Test, Air Permeability test (sportswear)</li> <li style="margin: 10px !important;">Colour matching/ colour staining tests</li> <li style="margin: 10px !important;">REACH – SVHC Test</li> <li style="margin: 10px !important;">RSL (Restricted Substance Test)</li> <li style="margin: 10px !important;">Appearance Test ( after wash/ dry clean cycle)</li> <li style="margin: 10px !important;">Flammability Testing (Apparels, Nightwear, Carpets, Soft Toys, etc.)</li> <li style="margin: 10px !important;">Zip Quality Test ( Zipper strength, Zipper reciprocation test)</li> <li style="margin: 10px !important;">Peel bond Strength Test (Pasted materials)</li> <li style="margin: 10px !important;">Azo test, PCP, Formaldehyde, APEO/ NPEO, phthalates, etc.</li> </ul> </div> </div>';

        //                 CKEDITOR.instances['about_us_details'].setData(html_ck);

        //                 var intro_ck = '<table style="border:none"> <tr> <td> <p style="text-transform: uppercase;"><b>Dear &nbsp;&nbsp;'+result.contact_name+',</b></p><p><b>'+result.customer_name+',</b></p><p><b>'+result.contact_address+'</b></p></td></tr></table> <p>We are pleased to offer you with our special price offer for merchandise quality testing for</p><table class="post_details"> <tr> <td colspan="2"><b>TO : '+result.customer_name+'</b></td></tr><tr> <td><b>ATTN : </b>&nbsp;&nbsp;'+result.salutation+'&nbsp;&nbsp;'+result.contact_name+'<br>&nbsp;&nbsp;'+result.contact_designation+'</td><td><b>FROM : </b>'+result.quote_created_user+'<br>'+result.admin_designation_name+'</td></tr><tr> <td><b>QUOTE NO : </b>&nbsp;&nbsp;'+result.reference_no+'</td><td><b>PAGES : </b>{nb}</td></tr><tr> <td><b>DATE : </b>&nbsp;&nbsp;'+result.quote_date+'</td><td><b>TEL NO : </b>&nbsp;&nbsp;'+result.admin_telephone+'</td></tr><tr> <td> <p><b>ADDRESS : </b>&nbsp;&nbsp;'+result.contact_address+'</p><p><b>Tel :</b>&nbsp;&nbsp;'+result.contact_telephone+'</p><p><b>Email :</b>&nbsp;&nbsp;'+result.contact_email+'</p></td><td> <b>EMAIL : &nbsp;&nbsp; '+result.admin_email+'</b> </td></tr><tr> <td><b>Discussion Dated :</b>&nbsp;&nbsp;'+result.discussion_date+version_number+'</td><td><b>Quote Validity :</b>&nbsp;&nbsp;'+result.quote_valid_date+'</td></tr><tr> <td colspan="2"><b>Remarks : </b>'+result.additional_notes+'</td></tr><tr> <td colspan="2"><b>Buyer/self reference : </b>'+result.buyer_self_ref+'</td></tr></table>';


        //                 CKEDITOR.instances['introduction'].setData(intro_ck);
        //                 $("#show_discount").val(result.show_discount).change();
        //             }
        //     });

        // })



        // approve quotes
        $('#approve_quotes_form').submit(function(e) {

            e.preventDefault();
            $.ajax({
                url: "<?php echo base_url('Quotes/approve_quotes') ?>",
                method: "post",
                data: new FormData(this),
                contentType: false,
                processData: false,
                success: function(data) {
                    var msg = $.parseJSON(data);
                    if (msg.status > 0) {
                        $.notify(msg.msg, 'success');
                        $('.approve_pdf').modal("hide");
                        location.reload();

                    } else {
                        $.notify(msg.msg, 'error');
                    }
                }
            })

        })


        $(document).on('click', '.approve_quotes_btn', function() {
            var quote_id = $(this).attr("data-id");
            check_approver(quote_id);
            $('.quote_id').val(quote_id);
            var url_preview = "<?php echo base_url('Quotes/GeneratePDF_quotes/') ?>" + quote_id;
            $('.preview_pdf').attr('src', url_preview);


        })


        function check_approver(quote_id) {
            const _tokken = $('meta[name="_tokken"]').attr('value');
            $.ajax({
                url: "<?php echo base_url('Quotes/check_approver') ?>",
                method: "post",
                data: {
                    _tokken: _tokken,
                    quote_id: quote_id
                },
                success: function(data) {
                    var data = $.parseJSON(data);
                    if (data) {

                        $('.approver_btn_approval').append('<button type="submit" class="btn btn-primary approve_quotes" >Approve</button>');
                        // $('.approve_quotes').attr('disabled', false);

                    } else {
                        $('.approve_quotes').remove();
                    }
                }
            });
            return false;
        }
        // revert quote


        $('#revert_quotes').submit(function(e) {

            e.preventDefault();
            $.ajax({
                url: "<?php echo base_url('Quotes/awaiting') ?>",
                method: "post",
                data: new FormData(this),
                contentType: false,
                processData: false,
                success: function(data) {
                    var msg = $.parseJSON(data);
                    if (msg.status > 0) {
                        $.notify(msg.msg, 'success');
                        $('.generate_quote_popup').modal("hide");
                        location.reload();

                    } else {
                        $.notify(msg.msg, 'error');
                    }
                }
            })

        })


        $('.revert_quotes_btn').on('click', function() {
            var quote_id = $(this).attr("data-id");
            $('.quote_id').val(quote_id);
        })
        // pdf

        $('.pdf_quotes_btn').on('click', function() {
            var src = $(this).attr('data-url');
            var quote_id = $(this).attr("data-id");
            $('.quote_id').val(quote_id);
            $('.quote_pdf').attr('src', src);
        })

        // qoute status
        var quotes_status = "<?php echo base64_decode($quote_status); ?>";
        quotes_status_dropdown(quotes_status);

        function quotes_status_dropdown(quotes_status) {

            var selectOP = "<option value=''>SEARCH BY STATUS</option>";
            var NoRecordOP = "<option value=''>No Record Found</option>";
            const _tokken = $('meta[name="_tokken"]').attr('value');
            $.ajax({
                url: "<?php echo base_url('Quotes/get_quotes_status') ?>",
                method: "post",
                data: {
                    _tokken: _tokken
                },
                success: function(data) {
                    var data = $.parseJSON(data);
                    $('.quote_status').html("");
                    $('.quote_status').append(selectOP);
                    if (data) {
                        $.each(data, (i, v) => {

                            if (quotes_status == v) {

                                selectOP = "<option value ='" + v + "' selected>" + v + "</option>";

                            } else {

                                selectOP = "<option value ='" + v + "'>" + v + "</option>";
                            }

                            $('.quote_status').append(selectOP);
                        })
                    } else {
                        $('.quote_status').append(NoRecordOP);
                    }
                }
            })



        }


    });

    var css = {
        "position": "absolute",
        "width": "95%",
        "font-size": "12px",
        "z-index": 999,
        "overflow-y": "auto",
        "overflow-x": "hidden",
        "max-height": "200px",
        "cursor": "pointer",
    };

    function getAutolist(hide_input, input, ul, li, where, like, select, table) {

        var base_url = $("body").attr("data-url");
        var hide_inputEvent = $("input." + hide_input);
        var inputEvent = $("input." + input);
        var ulEvent = $("ul." + ul);

        inputEvent.focusout(function() {
            ulEvent.fadeOut();
        });

        inputEvent.on("click keyup", function(e) {
            var me = $(this);
            var key = $(this).val();
            var _URL = base_url + "get_auto_list";
            const _tokken = $('meta[name="_tokken"]').attr("value");
            e.preventDefault();


            $.ajax({
                url: _URL,
                method: "POST",
                data: {
                    key: key,
                    where: where,
                    like: like,
                    select: select,
                    table: table,
                    _tokken: _tokken,
                },

                success: function(data) {
                    var html = $.parseJSON(data);
                    ulEvent.fadeIn();
                    ulEvent.css(css);
                    ulEvent.html("");
                    if (html) {
                        $.each(html, function(index, value) {
                            ulEvent.append(
                                $(
                                    '<li class="list-group-item ' +
                                    li +
                                    '"' +
                                    "data-id=" +
                                    value.id +
                                    ">" +
                                    value.name +
                                    "</li>"
                                )
                            );
                        });
                    } else {
                        ulEvent.append(
                            $(
                                '<li class="list-group-item ' +
                                li +
                                '"' +
                                'data-id="not">NO REORD FOUND</li>'
                            )
                        );
                    }

                    var liEvent = $("li." + li);
                    liEvent.click(function() {
                        var id = $(this).attr("data-id");
                        var name = $(this).text();
                        inputEvent.val(name);
                        hide_inputEvent.val(id);
                        ulEvent.fadeOut();
                    });

                    // ****
                },

            });
            e.stopimmediatepropagation();
            return false;
        });
    }
</script>
<script>
    $(document).ready(function() {
        const url = $('body').data('url');
        $('.gen_quotes_btn').click(function() {
            var quote_id = $(this).data('id');
            $('.quote_id').val(quote_id);
            // console.log(quote_id); return false;
            $('#pdf_view').html('<iframe width="100%" style="height:60vh;" src="' + url + 'Quotes/GeneratePDF_quotes/' + quote_id + '"></frame>')
        });

        $(document).on('submit', '#generate_quote', function(e) {
            $('body').append('<div class="pageloader"></div>');
            e.preventDefault();
            var form = $(this);
            $.ajax({
                type: 'post',
                url: form.attr('action'),
                data: form.serialize(),
                dataType: 'json',
                success: function(data) {
                    $('.pageloader').remove();
                    if (data.status > 0) {
                        $.notify(data.msg, "success");
                        window.setTimeout(function() {
                            window.location.reload();
                        }, 2000);
                    } else {
                        $.notify(data.msg, "error");
                    }
                },
                error: function(e) {
                    console.log(e);
                    $('.pageloader').remove();
                }
            });
        });
        $(document).on('click','.clone_quote',function(){
            const _tokken = $('meta[name="_tokken"]').attr('value');
            var quote_id = $(this).data('quote_id');
            if(confirm('Are you want to copy quotation')){
                $('body').append('<div class="pageloader"></div>');
                $.ajax({
                    type: 'post',
                    url: '<?php echo base_url("Quotes/clone_quotation")?>',
                    data:{_tokken: _tokken, quote_id:quote_id},
                    dataType:'json',
                    success:function(data){
                        $('.pageloader').remove();
                        if (data.status > 0) {
                            $.notify(data.message, "success");
                            window.setTimeout(function() {
                                window.location.replace(url + 'Quotes/index');
                            }, 2000);
                        } else {
                            $.notify(data.message, "error");
                        }
                    }
                })
            }
        });
    });
</script>