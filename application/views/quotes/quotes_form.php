<style>
    .currency_code {
        width: 50px;
        background-color: #B0B0B0;
    }

    .test_container {
        max-height: 250px;
        overflow: scroll;
        overflow-x: hidden;
    }


    .quotes_details_table {
        max-height: 250px;
        overflow: scroll;
        overflow-x: hidden;
    }

    .test_header_table th,
    .test_table td,
    .package_data td,
    .protocol_data td {
        width: 16%;
    }
</style>
<?php if ($branch_id) { ?>
    <script>
        $(document).ready(function() {
            $('.quotes_branch_dropdown').attr('disabled', true);
        })
    </script>
<?php } else { ?>
    <script>
        $(document).ready(function() {
            $('.quotes_branch_dropdown').attr('disabled', false);
        })
    </script>
<?php } ?>
<script src="<?php echo base_url('ckeditor/ckeditor.js'); ?>"></script>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>QUOTES</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard') ?>">HOME</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo base_url('Quotes') ?>">QUOTES</a></li>
                        <li class="breadcrumb-item active"><?php echo $title ?></li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>


    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"><?php echo $card_title ?></h3>
                        </div>

                        <form id="quotes" method="post" name="quotes_form" action="" enctype="multipart/form-data">
                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">

                            <input type="hidden" value="<?php echo $msg ?>" name="msg">

                            <input type="hidden" name="sample_type_id" value="<?php echo $sample_type_id ?>" class="sample_type_id">
                            <input type="hidden" name="sample_type_category_quote" value="" class="sample_type_category_quote">


                            <input type="hidden" name="quote_id" value="<?php echo $quote_id ?>" class="quote_id">

                            <div class="row p-2">
                                <div class="col-sm-6">
                                    <label for=""> Quote Ref. No.:</label>
                                    <input type="text" name="reference_no" id="" readonly class="form-control form-control-sm" style="background-color:#B0B0B0" value="<?php echo $reference_no; ?>">
                                </div>
                                <div class="col-sm-6">
                                    <label for="">Opportunity Name:</label>
                                    <input type="text" name="quotes_opportunity_name" id="" readonly class="form-control form-control-sm" style="background-color:#B0B0B0" value="<?php echo $opportunity_name ?>">
                                </div>

                            </div>

                            <div class="row p-2">
                                <div class="col-sm-6">
                                    <label for="">Customer Type:</label>
                                    <select name="customer_type" id="" class="form-control form-control-sm customer_type" value="">
                                        <option value="">Select</option>
                                        <option value="Factory" <?php echo ($customer_type == "Factory") ? "selected" : "" ?>>Factory</option>
                                        <option value="Buyer" <?php echo ($customer_type == "Buyer") ? "selected" : "" ?>>Buyer</option>
                                        <option value="Agent" <?php echo ($customer_type == "Agent") ? "selected" : "" ?>>Agent</option>
                                    </select>

                                </div>
                                <div class="col-sm-6">
                                    <label for="">Customer:</label>
                                    <div class="row ">

                                        <div class="col-sm-10">

                                            <select name="quotes_customer_id" id="" class="form-control form-control-sm customer">

                                            </select>

                                        </div>

                                        <div class="col-sm-2">
                                            <label for="">Add</label>
                                            <button type="button" class="bg-primary add_more_btn_cust" data-bs-toggle="modal" data-bs-target=".add_more_cust_popup">
                                                <img src="<?php echo base_url('assets/images/add.png') ?>" alt="Add more customer" title="ADD MORE CUSTOMERS">
                                            </button>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="row p-2">

                                <div class="col-sm-6">
                                    <label for="">Contact:</label>
                                    <div class="row">
                                        <div class="col-sm-10">

                                            <select name="quotes_contact_id" id="" class="form-control form-control-sm contacts">

                                            </select>

                                        </div>

                                        <div class="col-sm-2">
                                            <label for="">Add</label>
                                            <button type="button" class="bg-primary add_more_btn" data-bs-toggle="modal" data-bs-target=".add_more_contacts_popup">
                                                <img src="<?php echo base_url('assets/images/add.png') ?>" alt="Add more contacts" title="ADD MORE CONTACTS">
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label for="">Quote date:</label>
                                            <input type="date" name="quote_date" id="" value="<?php echo $quote_date ?>" class="form-control form-control-sm quote_data">

                                            </input>
                                        </div>

                                        <div class="col-sm-6">

                                            <label for="">Valid till:</label>
                                            <input type="date" name="quote_valid_date" id="" value="<?php echo $quote_valid_date ?>" class="form-control form-control-sm valid_till">

                                            </input>
                                        </div>
                                    </div>
                                </div>

                            </div>


                            <div class="row p-2">
                                <div class="col-sm-6">
                                    <label for="">Branch:</label>
                                    <select name="quotes_branch_id" value="" id="" class="quotes_branch_dropdown form-control" disabled>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label for="">Currency:</label>
                                            <select name="quotes_currency_id" id="quotes_currency_id" class="form-control form-control-sm currency" value="">

                                            </select>



                                        </div>
                                        <div class="col-sm-6">
                                            <label for="">Exchange Rate:</label>
                                            <input name="quote_exchange_rate" value="" id="" class="form-control form-control-sm ext_rate" style="background-color:#B0B0B0">

                                            </input>
                                        </div>

                                    </div>
                                </div>

                            </div>

                            <label class="p-2" for=""> Discussion Date:</label>

                            <div class="row p-2">

                                <div class="col-sm-3">

                                    <input type="date" name="discussion_date" id="" value="<?php echo $discussion_date ?>" class="form-control form-control-sm disc_date">
                                </div>

                                <div class="col-sm-2 quote_dtails">
                                    <button type="button" class="btn btn-info detail_quote_btn " data-bs-toggle="modal" data-bs-target=".quote_window" disabled>Add Quotes Details</button>

                                </div>

                                <!-- <div class="col-sm-2">

                                    <select name="" id="" class="form-control form-control-sm divsion_wise_discount" >
                                    </select>
                                </div> -->
                                <label for="" class="p-2">Common Discount</label>
                                <div class="col-sm-2">

                                    <input type="number" class="form-control form-control-sm division_discount" value="0" step="any">
                                </div>
                                <div class="col-sm-1">
                                    <button type="button" class="btn btn-sm btn-default add_division_discounts">ADD</button>
                                </div>

                                <!-- <div class="col-sm-2">
                                    <input type="checkbox" name="show_discount" id="" value="1" <?php echo ($item->show_discount == 1) ? 'checked' : ''; ?>> Show Discount in PDF
                                </div> -->

                                <div class="col-sm-2 delete_multiple_tests text-right">

                                    <label for="">DELETE ALL TESTS</label>
                                    <input type="checkbox" class="delete_test_all_checkbox">
                                    <button type="button" class="btn btn-sm btn-default delete_tests" title="Delete all" disabled>
                                        <img src="<?php echo base_url('assets/images/drop.png') ?>" alt="delete all">
                                    </button>
                                </div>

                            </div>

                            <div class="row p-2">
                                <div class="col-sm-3">
                                    <label for="">Show Test/Package/Protocol Price ?</label>
                                    <select name="show_price" id="" class="form-control form-control-sm">
                                        <option selected disabled>SELECT</option>
                                        <option value="1" <?php echo ($show_price == "1") ? 'selected' : ''; ?>>YES</option>
                                        <option value="0" <?php echo ($show_price == "0") ? 'selected' : ''; ?>>NO</option>
                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <label for="">Show Discount ?</label>
                                    <select name="show_discount" id="show_discount" class="form-control form-control-sm">
                                        <option selected disabled>SELECT</option>
                                        <option value="1" <?php echo ($show_discount == 1) ? 'selected' : ''; ?>>YES</option>
                                        <option value="0" <?php echo ($show_discount == 0) ? 'selected' : ''; ?>>NO</option>
                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <label for="">Show Test Method ?</label>
                                    <select name="show_test_method" id="" class="form-control form-control-sm">
                                        <option selected disabled>SELECT</option>
                                        <option value="1" <?php echo ($show_test_method == 1) ? 'selected' : ''; ?>>YES</option>
                                        <option value="0" <?php echo ($show_test_method == 0) ? 'selected' : ''; ?>>NO</option>
                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <label for="">Show Division ?</label>
                                    <select name="show_division" id="" class="form-control form-control-sm">
                                        <option selected disabled>SELECT</option>
                                        <option value="1" <?php echo ($show_division == 1) ? 'selected' : ''; ?>>YES</option>
                                        <option value="0" <?php echo ($show_division == 0) ? 'selected' : ''; ?>>NO</option>
                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <label for="">Show Total Amount ?</label>
                                    <select name="show_total_amount" id="" class="form-control form-control-sm">
                                        <option selected disabled>SELECT</option>
                                        <option value="1" <?php echo ($show_total_amount == 1) ? 'selected' : ''; ?>>YES</option>
                                        <option value="0" <?php echo ($show_total_amount == 0) ? 'selected' : ''; ?>>NO</option>
                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <label for="">Show Products ?</label>
                                    <select name="show_products" id="" class="form-control form-control-sm">
                                        <option selected disabled>SELECT</option>
                                        <option value="1" <?php echo ($show_products == 1) ? 'selected' : ''; ?>>YES</option>
                                        <option value="0" <?php echo ($show_products == 0) ? 'selected' : ''; ?>>NO</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="">SHOW ABOUT US ?</label>
                                    <select name="allow_about_us" id="" class="form-control form-control-sm">
                                        <option selected disabled>SELECT</option>
                                        <option value="1" <?php echo ($allow_about_us == 1) ? 'selected' : ''; ?>>YES</option>
                                        <option value="0" <?php echo ($allow_about_us == 0) ? 'selected' : ''; ?>>NO</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="">Mark selected test as Package/Protocol</label>
                                    <select name="make_package" id="make_package" class="form-control form-control-sm">
                                        <option selected disabled>SELECT</option>
                                        <option value="1" <?php echo ($mark_package == 1) ? 'selected' : ''; ?>>YES</option>
                                        <option value="0" <?php echo ($mark_package == 0) ? 'selected' : ''; ?>>NO</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row p-2">
                                <div class="col-md-3" id="choose_type" style="display: none;">
                                    <label for="">Choose Type</label>
                                    <select id="type_to_create" class="form-control form-control-sm">
                                        <option disabled selected>Choose</option>
                                        <option value="1">Package</option>
                                        <option value="2">Protocol</option>
                                    </select>
                                </div>
                                <div class="col-md-3" id="package_name" style="display: none;">
                                    <label for="">Package/Protocol Name</label>
                                    <input type="text" class="form-control form-control-sm package_name">
                                </div>
                                <div class="col-md-3" id="package_price" style="display: none;">
                                    <label for="">Package/Protocol Price</label>
                                    <input type="text" class="form-control form-control-sm package_price">
                                </div>
                                <div class="col-md-3" id="add_package_btn" style="display: none;">
                                    <input type="button" class="btn btn-sm btn-primary" style="margin-top: 25px;" value="Add" id="add_package">
                                </div>
                            </div>

                            <div class="row p-2">
                                <div class="col-sm-12">
                                    <div class="table-responsive test_header_table" style="font-size: 12px;">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Selected Test</th>
                                                    <th>Test Method</th>
                                                    <th>Test Division</th>
                                                    <th>Rate/Test/Sample</th>
                                                    <th>Discount %</th>
                                                    <th>Applicable charge</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                           <div class="row p-2">
                                <div class="col-sm-12">
                                    <div class="table-responsive quotes_details_table" style="font-size: 12px;">
                                        <b>
                                            <p>Test Details : </p>
                                        </b>
                                        <table class="table test_table table-sm">

                                            <tbody>

                                                <?php $i = 0;
                                                if ($test_data && count($test_data) > 0) { ?>
                                                    <?php foreach ($test_data as $key => $item) { ?>
                                                        <?php if ($item->id) : ?>
                                                            <tr>
                                                                <td>
                                                                    <input type='checkbox' class='test_list' value='<?php echo $item->test_id ?>' data-sample_type_id='<?php echo $item->sample_type_id ?>' data-price='<?php echo $item->applicable_charge ?>'>
                                                                    <input type='hidden' name='test_data[<?php echo $i ?>][test_division_id]' value='<?php echo $item->test_division_id ?>'>
                                                                    <input type='hidden' name='test_data[<?php echo $i ?>][work_sample_type_id]' value='<?php echo $item->sample_type_id ?>'>
                                                                    <input type='hidden' name='test_data[<?php echo $i ?>][test_id]' class='form-control form-control-sm' value='<?php echo $item->id ?>'><?php echo $item->name ?>
                                                                    <input type='hidden' name='test_data[<?php echo $i ?>][work_id]' value='<?php echo $item->work_id ?>'>
                                                                </td>
                                                                <td><input type='text' name='test_data[<?php echo $i ?>][test_method]' class='form-control form-control-sm' value='<?php echo $item->test_method ?>' readonly></td>
                                                                <td><input type='text' name='test_data[<?php echo $i ?>][work_division_name]' class='form-control form-control-sm' value='<?php echo $item->work_division_name ?>' readonly></td>
                                                                <td><input type='number' name='test_data[<?php echo $i ?>][price]' class='form-control form-control-sm rate' value='<?php echo $item->price ?>' readonly style='background-color:#B0B0B0'></td>
                                                                <td><input type='number' name='test_data[<?php echo $i ?>][discount]' class='form-control form-control-sm discount' value='<?php echo $item->discount ?>'></td>
                                                                <td><input type='number' name='test_data[<?php echo $i ?>][applicable_charge]' class='form-control form-control-sm applicable_charge' value='<?php echo $item->applicable_charge ?>' readonly></td>
                                                                <td><button type='button' value='<?php echo $item->test_id ?>' title='Remove' class='del_test btn btn-default btn-sm'><img src='<?php echo base_url('assets/images/del.png') ?>'></button></td>
                                                            </tr>
                                                        <?php endif; ?>
                                                    <?php $i++;
                                                    } ?>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                        <b>
                                            <p>Package Details : </p>
                                        </b>
                                        <table class="table package_data table-sm">
                                            <tbody>
                                                <?php $i = 0;
                                                if ($package_data && count($package_data) > 0) { ?>
                                                    <?php foreach ($package_data as $key => $item) { ?>
                                                        <tr>
                                                            <td><input type='hidden' name='package_data[<?php echo $i ?>][test_division_id]' value='<?php echo $item->test_division_id ?>'>
                                                                <input type='hidden' name='package_data[<?php echo $i ?>][works_sample_type_id]' class='form-control form-control-sm' value='<?php echo $item->works_sample_type_id ?>'>
                                                                <input type='hidden' name='package_data[<?php echo $i ?>][test_id]' class='form-control form-control-sm' value='<?php echo $item->test_id ?>'>
                                                                <input type='hidden' name='package_data[<?php echo $i ?>][work_id]' value='<?php echo $item->work_id ?>'><?php echo $item->test_name ?>
                                                            </td>
                                                            <td><input type='text' name='package_data[<?php echo $i ?>][test_method]' class='form-control form-control-sm' value='<?php echo $item->test_method ?>' readonly></td>
                                                            <input type='hidden' name='package_data[<?php echo $i ?>][package_id]' class='form-control form-control-sm' value='<?php echo $item->package_id ?>' readonly>
                                                            <td><input type='text' name='package_data[<?php echo $i ?>][work_division_name]' class='form-control form-control-sm' value='<?php echo $item->work_division_name ?>' readonly></td>
                                                            <?php if ($key == 0) { ?>
                                                                <td><input type='number' name='package_data[<?php echo $i ?>][price]' class='form-control form-control-sm rate' value='<?php echo $item->rate ?>' readonly style='background-color:#B0B0B0'></td>
                                                                <td><input type='number' name='package_data[<?php echo $i ?>][discount]' class='form-control form-control-sm discount' value='<?php echo $item->discount ?>' disabled></td>
                                                                <td><input type='number' name='package_data[<?php echo $i ?>][total_cost]' class='form-control form-control-sm applicable_charge' value='<?php echo $item->total_cost ?>' readonly></td>
                                                                <td><button type='button' value='<?php echo $item->package_id ?>' title='Remove' class='del_test btn btn-default btn-sm' data-value='<?php echo count($package_data) ?>'><img src="<?php echo base_url('assets/images/del.png') ?>"></button></td>
                                                            <?php } ?>
                                                        </tr>
                                                    <?php $i++;
                                                    } ?>

                                                <?php } ?>


                                            </tbody>
                                        </table>

                                        <b>
                                            <p>Protocol Details : </p>
                                        </b>
                                        <table class="table protocol_data table-sm">

                                            <tbody>
                                                <?php $i = 0;
                                                if ($protocol_data && count($protocol_data) > 0) { ?>
                                                    <?php foreach ($protocol_data as $key => $item) { ?>

                                                        <tr>
                                                            <td><input type='hidden' name='protocol_data[<?php echo $i ?>][test_division_id]' value='<?php echo $item->test_division_id ?>'>
                                                                <input type='hidden' name='protocol_data[<?php echo $i ?>][works_sample_type_id]' class='form-control form-control-sm' value='<?php echo $item->works_sample_type_id ?>'>
                                                                <input type='hidden' name='protocol_data[<?php echo $i ?>][test_id]' class='form-control form-control-sm' value='<?php echo $item->test_id ?>'>
                                                                <input type='hidden' name='protocol_data[<?php echo $i ?>][work_id]' value='<?php echo $item->work_id ?>'><?php echo $item->test_name ?>
                                                            </td>


                                                            <td><input type='text' name='protocol_data[<?php echo $i ?>][test_method]' class='form-control form-control-sm' value='<?php echo $item->test_method ?>' readonly></td>


                                                            <input type='hidden' name='protocol_data[<?php echo $i ?>][protocol_id]' class='form-control form-control-sm' value='<?php echo $item->protocol_id ?>' readonly>

                                                            <td><input type='text' name='protocol_data[<?php echo $i ?>][work_division_name]' class='form-control form-control-sm' value='<?php echo $item->work_division_name ?>' readonly></td>


                                                            <?php if ($key == 0) { ?>
                                                                <td><input type='number' name='protocol_data[<?php echo $i ?>][price]' class='form-control form-control-sm rate' value='<?php echo $item->rate ?>' readonly style='background-color:#B0B0B0'></td>

                                                                <td><input type='number' name='protocol_data[<?php echo $i ?>][discount]' class='form-control form-control-sm discount' value='<?php echo $item->discount ?>'></td>

                                                                <td><input type='number' name='protocol_data[<?php echo $i ?>][total_cost]' class='form-control form-control-sm applicable_charge' value='<?php echo $item->total_cost ?>' readonly></td>

                                                                <td><button type='button' value='<?php echo $item->protocol_id ?>' title='Remove' class='del_test btn btn-default btn-sm' data-value='<?php echo count($protocol_data) ?>'><img src="<?php echo base_url('assets/images/del.png') ?>"></button></td>

                                                            <?php } ?>
                                                        </tr>
                                                    <?php $i++;
                                                    } ?>

                                                <?php } ?>
                                            </tbody>
                                        </table>


                                    </div>
                                </div>
                            </div>


                            <div class="row p-2">
                                <div class="col-sm-6">
                                    <label for="">Approver's Designation:</label>
                                    <select name="quote_signing_authority_designation_id" class="form-control form-control-sm approver_dsg" value="<?php $desgination_id ?>" id="">
                                    </select>

                                </div>

                                <div class="col-sm-6">
                                    <label for="">Total Value:</label>
                                    <div class="row">
                                        <div class="col-sm-10">
                                            <input type="text" name="quote_value" id="" readonly class="form-control form-control-sm quote_value" style="background-color:#B0B0B0" value="">
                                        </div>

                                        <div class="col-sm-2">
                                            <div class="">
                                                <input type="text" width="50%" class="currency_code" readonly value="">
                                            </div>
                                        </div>
                                    </div>


                                </div>
                            </div>


                            <div class="row p-2">
                                <div class="col-sm-6">
                                    <label for="">Approver:</label>
                                    <select name="quotes_signing_authority_id" class="form-control form-control-sm approver" value="<?php echo $approver_id ?>" id="">
                                    </select>

                                </div>

                                <div class="col-sm-6">
                                    <label for="">Upload Files:</label>
                                    <input type="file" class="form-control form-control-sm" accept=".pdf" name="attach_file">
                                    </select>
                                </div>

                            </div>




                            <div class="row p-2">

                                <div class="col-sm-6">
                                    <label for="">Quote Subject</label>
                                    <textarea class="ckeditor" name="quote_subject" id="quote_subject"><?php echo $quote_subject ?></textarea>
                                </div>

                                <div class="col-sm-6">
                                    <label for="">Salutation and Greetings</label>
                                    <textarea class="ckeditor" name="salutation" id="salutation"><?php echo $salutation ?></textarea>
                                </div>

                            </div>

                            <div class="row p-2">

                                <div class="col-sm-6">
                                    <label for="">Terms and Conditions</label>
                                    <textarea class="ckeditor" name="terms_conditions" id="terms_conditions"><?php echo $terms_conditions ?></textarea>
                                </div>

                                <div class="col-sm-6">
                                    <label for="">Payment Terms</label>
                                    <textarea class="ckeditor" name="payment_terms" id="payment_terms"><?php echo $payment_terms ?></textarea>
                                </div>

                            </div>

                            <div class="row p-2">

                                <div class="col-sm-6">
                                    <label for="">Sample Retention</label>
                                    <textarea class="ckeditor" name="sample_retention" id="sample_retention"><?php echo $sample_retention ?></textarea>
                                </div>

                                <div class="col-sm-6">
                                    <label for="">Remarks</label>
                                    <textarea class="ckeditor" name="additional_notes" id="additional_notes"><?php echo $additional_notes ?></textarea>
                                </div>

                            </div>

                            <div class="row p-2">
                                <div class="col-sm-12">
                                    <label for="">Select Division For Contact Details</label>
                                    <select name="contact_division" class="form-control" id="contact_division"></select>
                                </div>
                                <div class="col-sm-6">
                                    <label for="">Notes Details</label>
                                    <textarea name="notes_details" class="ckeditor notes_details">

                                    <p><b><u><i>Notes - </i></u></b></p><ul class="notes_list"><li>For any test, which is not listed above will be offered with flat – 25% discount on our base price.</li><li>Price quoted above are excluding service tax.</li><li>Prices offered are based upon testing per component per material.</li><li>Composite testing will be performed upto maximum of three homogenous material together OR as per allowance under buyer’s manual.</li><li>Turn around time – 3 working days (excluding tests with increase turn around time).</li><li>Sample pick up facility at your door step</li><li>Credit Period – 30 days.</li></ul>
                                    </textarea>
                                </div>

                                <div class="col-sm-6">
                                    <label for="">Contact Details</label>
                                    <textarea class="ckeditor contact_details" name="contact_details">
                                    <?php echo $contact_details ?>
                                    </textarea>
                                </div>
                            </div>

                            <div class="row p-2">

                                <div class="col-sm-12">
                                    <label for="">Buyer/self reference</label>
                                    <textarea name="buyer_self_ref" class="form-control"><?php echo $ref; ?></textarea>
                                </div>

                            </div>

                            <div class="row p-2 text-right">
                                <div class="col-sm-12">

                                    <a href="<?php echo base_url('quotes') ?>" class="btn btn-primary">Back</a>
                                    <button class="btn btn-primary" type="submit" id='quotes_submit_button'>
                                        Submit
                                    </button>
                                </div>
                            </div>
                        </form>

                    </div>
                    <!-- /.card -->

                </div>
                <!--/.col (left) -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>


<!-- popups -->
<!-- quote popups -->

<div class="modal fade bd-example-modal-lg quote_window" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Quote Details</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="details_quote_reset">
                <div class="modal-body">

                    <div class="row p-2">
                        <div class="col-sm-6">
                            <select name="" id="" class="form-control form-control-sm quotes_details_type">
                                <option value="" selected>Select Quotes Type</option>
                                <option value="PR">Products</option>
                                <option value="DQ">Division Quote</option>
                                <!-- <option value="SP">Sample Pickup</option> -->
                            </select>
                        </div>


                    </div>

                    <div class="row p-2 quotes_details">

                    </div>

                    <div class="row p-2 ">
                        <div class="col-sm-12">
                            <div class="manage_details"></div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary quotes_submit">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-lg add_more_contacts_popup" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Contact</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="add_contact_form" name="add_contact_name" action="javascript:void(0);">
                    <input type="hidden" name="contacts_customer_id" class="contacts_customer_id" value="">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">

                    <div class="row">
                        <div class="col-sm-6">
                            <label for=""> Customer Type:</label>
                            <input type="text" name="customer_type" id="" value="" readonly class="form-control form-control-sm contact_customer_type" style="background-color:#B0B0B0">
                        </div>
                        <div class="col-sm-6">
                            <label for="">Customer Name</label>
                            <input type="text" name="" id="" value="" readonly class="form-control form-control-sm contact_customer_name" style="background-color:#B0B0B0">
                        </div>

                    </div>


                    <div class="row p-2">
                        <div class="col-sm-6">
                            <label for="">Salutation:</label>
                            <input type="text" name="contact_salutation" class="form-control form-control-sm" placeholder="" value="">
                        </div>

                        <div class="col-sm-6">
                            <label for="">Country:</label>
                            <select name="country_id" id="" class="form-control form-control-sm country">

                            </select>
                        </div>
                    </div>

                    <div class="row p-2">
                        <div class="col-sm-6">
                            <label for="">Contact Name:</label>
                            <input type="text" name="contact_name" class="form-control form-control-sm" placeholder="" value="">

                        </div>

                        <div class="col-sm-6">
                            <label for="">State:</label>
                            <select name="" id="" class="form-control form-control-sm state">

                            </select>
                        </div>
                    </div>

                    <div class="row p-2">
                        <div class="col-sm-6">
                            <label for="">Designation:</label>
                            <input type="text" name="contacts_designation_id" class="form-control form-control-sm" placeholder="" value="">
                        </div>

                        <div class="col-sm-6">
                            <label for="">Type:</label>
                            <select name="type" id="" class="form-control form-control-sm">
                                <option value="" selected disabled>Select</option>
                                <option value="0">None</option>
                                <option value="1">Technical</option>
                                <option value="2">Report</option>
                                <option value="3">Invoice</option>
                                <option value="4">Payment follow-up</option>
                                <option value="5">Alternative</option>
                                <option value="6">Invoice follow-up</option>
                                <option value="7">site/sampling</option>

                            </select>
                        </div>
                    </div>

                    <div class="row p-2">
                        <div class="col-sm-6">
                            <label for="">Email</label>
                            <input type="email" name="email" class="form-control form-control-sm" placeholder="" value="">
                        </div>

                        <div class="col-sm-6">
                            <label for="">Status:</label>
                            <select name="status" id="" class="form-control form-control-sm">
                                <option value="1">Active</option>
                                <option value="0">In-Active</option>
                            </select>
                        </div>

                    </div>

                    <div class="row p-2">
                        <div class="col-sm-6">
                            <label for="">Telephone:</label>
                            <input type="number" name="telephone" class="form-control form-control-sm" placeholder="" value="">
                        </div>

                        <div class="col-sm-6">
                            <label for="">Mobile:</label>
                            <input type="text" name="mobile_no" class="form-control form-control-sm" placeholder="" value="">
                        </div>
                    </div>

                    <div class="row p-2">
                        <div class="col-sm-12">
                            <label for="">Note:</label>
                            <textarea name="note" id="" cols="30" rows="3" class="form-control form-control-sm "></textarea>
                        </div>
                    </div>


                    <div class="row p-2">
                        <div class="col-sm-6">
                            <label for="">Login Required:</label>
                            <input type="checkbox" name="contact_login_req" value="1" id="">
                        </div>
                    </div>



            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary contactsSumbit">ADD</button>
            </div>
            </form>
        </div>
    </div>
</div>

<!-- CUSTOMER POPUP -->

<div class="modal fade bd-example-modal-lg add_more_cust_popup" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Customer</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="add_customer_form" name="add_customer_name" action="javascript:void(0);">
                    <input type="hidden" name="" class="" value="">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">

                    <div class="row">
                        <div class="col-sm-6">
                            <label for=""> Customer Type:</label>
                            <input type="text" name="customer_type" id="" value="" readonly class="form-control form-control-sm cust_customer_type" style="background-color:#B0B0B0">
                        </div>
                        <div class="col-sm-6">
                            <label for="">Customer Name</label>
                            <input type="text" name="customer_name" id="" value="" class="form-control form-control-sm cust_customer_name">
                        </div>

                    </div>


                    <div class="row p-2">
                        <div class="col-sm-6">
                            <label for="">Email</label>
                            <input type="email" name="email" class="form-control form-control-sm" placeholder="" value="">
                        </div>

                        <div class="col-sm-6">
                            <label for="">Telephone:</label>
                            <input type="number" name="telephone" class="form-control form-control-sm" placeholder="" value="">
                        </div>
                    </div>

                    <div class="row p-2">
                        <div class="col-sm-6">
                            <label for="">Mobile:</label>
                            <input type="text" name="mobile" class="form-control form-control-sm" placeholder="" value="">
                        </div>

                        <div class="col-sm-6">
                            <label for="">Address:</label>
                            <textarea name="address" id="" cols="30" rows="2" class="form-control form-control-sm "></textarea>
                        </div>
                    </div>

                    <div class="row p-2">
                        <div class="col-sm-6">
                            <label for="">City:</label>
                            <input type="text" name="city" class="form-control form-control-sm" placeholder="" value="">
                        </div>

                        <div class="col-sm-6">
                            <label for="">Pin No.:</label>
                            <input type="text" name="po_box" class="form-control form-control-sm" placeholder="" value="">
                        </div>
                    </div>

                    <div class="row p-2">
                        <div class="col-sm-6">
                            <label for="">Country:</label>
                            <select name="cust_customers_country_id" id="" class="form-control form-control-sm country">

                            </select>
                        </div>

                        <div class="col-sm-6">
                            <label for="">State:</label>
                            <select name="cust_customers_province_id" id="" class="form-control form-control-sm state">

                            </select>
                        </div>
                    </div>


                    <div class="row p-2">

                        <div class="col-sm-6">
                            <label for="">Area/Location:</label>
                            <select name="cust_customers_location_id" id="" class="form-control form-control-sm area">

                            </select>


                        </div>

                        <div class="col-sm-6">
                            <label for="">Website:</label>
                            <input type="text" name="web" class="form-control form-control-sm" placeholder="" value="">
                        </div>

                    </div>

                    <div class="row p-2">


                        <div class="col-sm-6">
                            <label for="">Status:</label>
                            <select name="isactive" id="" class="form-control form-control-sm">
                                <option value="1">Active</option>
                                <option value="0">In-Active</option>
                            </select>
                        </div>

                        <div class="col-sm-6">
                            <label for="">Credit</label>
                            <select name="credit" id="" class="form-control form-control-sm">
                                <option value="" selected disabled>Select</option>
                                <option value="Advance">Advance</option>
                                <option value="30 Days">30 Days</option>
                                <option value="45 Days">45 Days</option>
                            </select>
                        </div>


                    </div>


                    <div class="row p-2">
                        <div class="col-sm-6">
                            <label for="">PAN NO.</label>
                            <input type="text" name="pan" class="form-control form-control-sm pan" placeholder="Enter PAN NO." value="">
                        </div>
                        <div class="col-sm-6">
                            <label for="">TAN NO.</label>
                            <input type="text" name="tan" class="form-control form-control-sm tan" placeholder="Enter TAN NO." value="">
                        </div>
                    </div>

                    <div class="row p-2">

                        <div class="col-sm-6">
                            <label for="">GSTIN</label>
                            <input type="text" name="gstin" class="form-control form-control-sm gstin" placeholder="Enter GSTIN NO." value="">
                        </div>
                    </div>



            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary customersSumbit">ADD</button>
            </div>
            </form>
        </div>
    </div>
</div>
<!-- popups end -->
<script>
    $(document).ready(function() {

        $('.discount').attr("disabled", true);
        add_discount_division_wise_dropdown();

        var customer_type = $('.customer_type').val();
        if (customer_type) {
            var customer_id = "<?php echo $customer_id ?>";

            if (customer_id != "") {
                get_customer_by_type(customer_type, customer_id);
                var contact_id = "<?php echo $contact_id ?>";
                if (contact_id != "") {
                    get_contact_by_customer_id(customer_id, contact_id);
                }
            }
        }

        update_quote_value();

        PR_html = '<div class="col-sm-4">';
        PR_html += '<label for="">Product Category:</label>';
        PR_html += '<select name="category" id="" class="form-control form-control-sm product_cat" value=""></select>';
        PR_html += '</div>';

        PR_html += '<div class="col-sm-4">';
        PR_html += '<label for="">Product:</label>';
        PR_html += '<select name="product" id="" class="form-control form-control-sm product" value=""></select>';
        PR_html += '</div>';

        PR_html += '<div class="col-sm-4">';
        PR_html += '<label for="">Product Type:</label>';
        PR_html += '<select name="product_type" id="" class="form-control form-control-sm product_type" value="">';
        PR_html += '</select>';
        PR_html += '</div>';

        // division html


        DQ_div_html = '<div class="col-sm-12">';
        DQ_div_html += '<label for=""><b>Select Division:</b></label>&nbsp&nbsp&nbspSelect All&nbsp&nbsp<input type="checkbox" value="1" class="selectall_div">';
        DQ_div_html += '<select  name="divison" class="form-control form-control-sm division_select" value="" multiple></select>';
        DQ_div_html += '</div>';
        DQ_div_html += '<div class="col-sm-12 div_product">';
        DQ_div_html += '</div>';
        DQ_div_html += '<div class="col-sm-12 div_test">';
        DQ_div_html += '</div>';


        DQ_product_html = '<label for=""><b>Select Product:</b></label>&nbsp&nbsp&nbspSelect All&nbsp&nbsp<input type="checkbox" value="1" class="selectall_div_product">';
        DQ_product_html += '<select  name="divison_product" class="form-control form-control-sm division_select_product" value="" multiple></select>';



        DQ_test_html = '<label for=""><b>Select Test:</b></label>&nbsp&nbsp&nbspSelect All&nbsp&nbsp<input type="checkbox" value="1" class="selectall_div_test">';
        DQ_test_html += '<select  name="divison_test" class="form-control form-control-sm division_select_test" value="" multiple></select>';


        var q_type = $('.quotes_details_type');
        var q_detail = $('.quotes_details');
        q_type.on('change', function() {

            var d_value = $(this).val();
            if (d_value == "PR") {
                // $('.divsion_wise_discount').attr('disabled', true);
                // $('.division_discount').attr('disabled', false);
                // $('.add_division_discounts').attr('disabled', true);
                $('.manage_details').html("");
                q_detail.html("");
                q_detail.html(PR_html);
                get_product_cat();
            }
            if (d_value == "DQ") {
                add_discount_division_wise_dropdown();
                // $('.divsion_wise_discount').attr('disabled', false);
                // $('.division_discount').attr('disabled', false);
                // $('.add_division_discounts').attr('disabled', false);
                q_detail.html("");
                q_detail.html(DQ_div_html);
                $('.division_select').select2();
                load_division();
                $('.manage_details').html("");

                $(document).on('change', '.division_select', function() {

                    var div_ids = $(this).val();
                    $('.div_product').html("");
                    $('.div_product').html(DQ_product_html);
                    $('.division_select_product').select2();
                    get_product_by_division(div_ids)
                })

                $(document).on('change', '.division_select_product', function() {
                    var sample_type_id = $(this).val();
                    var currency_id = $('.currency').val();
                    var div_id = $('.division_select').val();
                    $('.quotes_submit').css("display", "none");
                    $('.manage_details').html("");
                    test_HTML();
                    get_tests_by_division(sample_type_id, currency_id, div_id);
                })
            }
            if (d_value == "") {
                // $('.divsion_wise_discount').attr('disabled', true);
                // $('.division_discount').attr('disabled', true);
                // $('.add_division_discounts').attr('disabled', true);
                $('.quotes_details').html("");
            }
        })


        function test_HTML() {
            var manage_div = "<div class='row'>";

            manage_div += "<div class='col-sm-6'>";
            manage_div += "<label>Tests:</label>";
            manage_div += "Select All<input type='checkbox' class='select_all_divi' value='1'></input>";
            manage_div += "</div></div>";

            manage_div += "<div class='row'>";

            manage_div += "<div class='col-sm-10'>";
            manage_div += "<select class='list-group-item manageTest' multiple='true' value=''></select>";
            manage_div += "</div>";

            manage_div += "<div class='col-sm-2'>";
            manage_div += "<button type='button' class='btn btn-sm bg-primary add_test_button_division' disabled>ADD</button>";
            manage_div += "&nbsp&nbsp&nbsp<button type='button' class='btn btn-sm bg-info add_extra_test_division'>ADD MORE TEST</button>";
            manage_div += "</div>";

            manage_div += "</div>";

            manage_div += "</div>";
            $('.manage_details').html(manage_div);
            $('.manageTest').select2();
        }


        function get_product_by_division(div_ids, selected = null) {
            const _tokken = $('meta[name="_tokken"]').attr("value");
            $.ajax({
                url: "<?php echo base_url('Quotes/get_products_by_division') ?>",
                method: "POST",
                data: {
                    div_ids: div_ids,
                    _tokken: _tokken,

                },
                success: function(response) {
                    var data = $.parseJSON(response);
                    $('.division_select_product').html("");
                    if (data) {
                        $.each(data, function(index, value) {
                            if (selected) {
                                var option = "<option value = '" + value.product_id + "' selected>" + value.product_name + "</option>";
                            } else {
                                var option = "<option value = '" + value.product_id + "'>" + value.product_name + "</option>";
                            }

                            $('.division_select_product').append(option);
                        })
                    } else {
                        var option = "<option value = '' disabled>NO RECORD FOUND</option>";
                        $('.division_select_product').append(option);
                    }
                }
            });
            return false;
        }

        function add_selected_division(div_ids) {
            const _tokken = $('meta[name="_tokken"]').attr("value");
            $.ajax({
                url: "<?php echo base_url('Quotes/load_divisions_selected') ?>",
                method: "POST",
                data: {
                    div_ids: div_ids,
                    _tokken: _tokken
                },
                success: function(response) {
                    var data = $.parseJSON(response);
                    $('.quote_window').modal('hide');
                    $('.test_table').remove();
                    $('.package_data').remove();
                    $('.protocol_data').remove();
                    $('.division_data').html("");
                    thead = "<thead><tr>";
                    thead += "<th>Division Name</th>";
                    thead += "<th>Discount</th>";
                    thead += "<th>Action</th>";
                    thead += "</tr></thead>";
                    $('.division_data').append(thead);
                    tbody = "<tbody>";
                    tbody += "</tbody>";
                    $('.division_data').append(tbody);
                    var delIcon = '<?php echo base_url('assets/images/del.png') ?>';
                    $.each(data, function(index, value) {
                        tbody = "<tr>";
                        tbody += "<td><input type='hidden' name='division_data[" + index + "][division_id]' value='" + value.division_id + "'><input type='text' class='form-control form-control-sm' name='division_data[" + index + "][division_name]' value='" + value.division_name + "' readonly></td>";
                        tbody += "<td><input type='number' class='form-control form-control-sm' value='0' name='division_data[" + index + "][discount]'></td>";
                        tbody += "<td><button type='button' value='" + value.division_id + "' title='Remove' class='del_test btn btn-default btn-sm'><img src='" + delIcon + "'></button></td>";
                        tbody += "</tr>";
                        $('.division_data tbody').append(tbody);

                    })

                }
            });
            return false;
        }


        $(document).on('change', '.selectall_div', function() {
            if (this.checked == true) {
                load_division(true);

                setTimeout(() => {
                    $('.div_product').html("");
                    $('.div_product').html(DQ_product_html);
                    $('.division_select_product').select2();
                    var div_ids = $('.division_select').val();
                    get_product_by_division(div_ids);
                }, 1000);


            } else {
                load_division();
                get_product_by_division('');
            }
        })



        $(document).on('change', '.selectall_div_product', function() {
            var div_id = $('.division_select').val();
            if (this.checked == true) {
                get_product_by_division(div_id, true);
                setTimeout(() => {
                    var sample_type_id = $('.division_select_product').val();
                    var currency_id = $('.currency').val();
                    test_HTML();
                    get_tests_by_division(sample_type_id, currency_id, div_id);
                }, 1000);

            } else {
                get_product_by_division(div_id);
                $('.manage_details').html("");
            }
        })

        function load_division(selected = null) {

            const _tokken = $('meta[name="_tokken"]').attr("value");
            $.ajax({
                url: "<?php echo base_url('Quotes/load_divisions') ?>",
                method: "GET",
                success: function(response) {
                    var data = $.parseJSON(response);
                    $('.division_select').html("");
                    option = "<option disabled value=''>SELECT DIVISION</option>";
                    $('.division_select').append(option);
                    $.each(data, function(index, value) {
                        if (selected) {
                            option = '<option value="' + value.division_id + '" selected>' + value.division_name + '</option>';
                        } else {
                            option = '<option value="' + value.division_id + '">' + value.division_name + '</option>';
                        }
                        $('.division_select').append(option);


                    })
                }
            });
            return false;
        }


        $(document).on('click', '.quotes_submit', function() {

            var product_type = $('.product_type').val();

            if (product_type == '1') {
                var tableHtml = $('.test_container').children("table").children("tbody").html();
                var manage_details = $('.manage_details').html();
                if (manage_details == "") {
                    $.notify('Cannot save empty details', 'error');
                } else {

                    $('.package_data tbody').html("");
                    var c = $('.quotes_details_table table.package_data tbody');
                    //    c.append("<tbody></tbody>");
                    c.append(tableHtml);
                    // $('.quotes_details_table table.test_table tbody').html("");
                    // $('.quotes_details_table table.protocol_data tbody').html("");
                    $('.quote_window').modal("hide");
                    $.notify('Product Details saved successfully', 'success');
                    $('.manage_details').html("");
                }
                update_quote_value();
            }
            if (product_type == '2') {
                var tableHtml = $('.test_container').children("table").children("tbody").html();

                var manage_details = $('.manage_details').html();
                if (manage_details == "") {
                    $.notify('Cannot save empty details', 'error');
                } else {

                    var c = $('.quotes_details_table table.protocol_data tbody');

                    c.html(tableHtml);
                    // $('.quotes_details_table table.test_table tbody').html("");
                    // $('.quotes_details_table table.package_data tbody').html("");

                    $('.quote_window').modal("hide");
                    $.notify('Product Details saved successfully', 'success');
                    $('.manage_details').html("");
                }
                update_quote_value();
            }
            if (product_type == '0') {
                var tableHtml = $('.test_container').children("table").children("tbody").html();
                var manage_details = $('.manage_details').html();
                if (manage_details == "") {
                    $.notify('Cannot save empty details', 'error');
                } else {

                    var c = $('.quotes_details_table table.test_data tbody');
                    //    c.append("<tbody></tbody>");
                    c.html(tableHtml);
                    // $('.quotes_details_table table.protocol_data tbody').html("");
                    // $('.quotes_details_table table.package_data tbody').html("");

                    $('.quote_window').modal("hide");
                    $.notify('Product Details saved successfully', 'success');
                    $('.manage_details').html("");
                }
                update_quote_value();
            }
        })

        $(document).on('change', '.product_type', function() {
            var product_type = $(this).val();
            $('.sample_type').val(product_type);
            var sample_type_id = $('.product').val();
            var currency_id = $('.currency').val();



            if (product_type == "0") {
                $('.quotes_submit').css("display", "none");
                $('.manage_details').html("");
                var manage_div = "<div class='row'>";

                manage_div += "<div class='col-sm-6'>";
                manage_div += "<label>Tests:</label>";
                manage_div += "Select All<input type='checkbox' class='select_all' value='1'></input>";
                manage_div += "</div></div>";

                manage_div += "<div class='row'>";

                manage_div += "<div class='col-sm-10'>";
                manage_div += "<select class='list-group-item manageTest' multiple='true' value=''></select>";
                manage_div += "</div>";

                manage_div += "<div class='col-sm-2'>";
                manage_div += "<button type='button' class='btn btn-sm bg-primary add_test_button' disabled>ADD</button>";
                manage_div += "&nbsp&nbsp&nbsp<button type='button' class='btn btn-sm bg-info add_extra_test'>ADD MORE TEST</button>";
                manage_div += "</div>";

                manage_div += "</div>";

                manage_div += "</div>";

                $('.manage_details').html(manage_div);
                $('.manageTest').select2();
                get_tests(sample_type_id, currency_id);

            } else {
                $('.quotes_submit').css("display", "block");
                var manage_div = "<div class='row'>";

                manage_div += "<div class='col-sm-6'>";
                manage_div += "<label>Package/Protocol:</label>";
                manage_div += "<select class='form-control form-control-sm managePackagesProtocol' value=''></select>";
                manage_div += "</div>";

                manage_div += "<div class='col-sm-6'>";
                manage_div += "<label>Price:</label>";
                manage_div += "<input type='number' name='' value='0' class='form-control form-control-sm price_packagesProto' style='background-color:#B0B0B0' readonly>";
                manage_div += "</div>";

                manage_div += "</div>";

                manage_div += "<div class='row'>";

                manage_div += "<div class='col-sm-6'>";
                manage_div += "<label>Discount %:</label>";
                manage_div += "<input type='number' name='' value='0' disabled class='form-control form-control-sm discount_packagesProto' >";
                manage_div += "</div>";

                manage_div += "<div class='col-sm-6'>";
                manage_div += "<label>Applicable Charge:</label>";
                manage_div += "<input type='number' name='' value='0' class='form-control form-control-sm charge_packagesProto' style='background-color:#B0B0B0' readonly>";
                manage_div += "</div>";

                manage_div += "</div>";

                manage_div += "<div class='row'>";

                manage_div += "<div class='col-sm-12'>";
                manage_div += "<div class='test_container table-responsive'>";
                manage_div += "</div></div>";

                manage_div += "</div>";

                $('.manage_details').html(manage_div);
                get_packagesProtocols(product_type, currency_id, sample_type_id);

            }

            if (product_type == '') {
                $('.manage_details').html("");
            }

        })
        $('.manageTest').select2();


        // get currency
        var currency_id = "<?php echo $currency_id ?>";
        get_currency(currency_id);

        $(document).on('change', '.currency', function() {
            var rt = $(this).find(':selected').attr('data-id');
            var code = $(this).find(':selected').attr('data-code');
            $('.ext_rate').val(rt);
            $('.currency_code').val(code);
            $('.test_table tbody').html("");
            $('.package_data tbody').html("");
            $('.protocol_data tbody').html("");
            $('.quote_value').val("0");
        })

        $('.customer_type').on('change', function() {
            var type = $(this).val();
            var customer_id = "";
            get_customer_by_type(type, customer_id);
        })

        var d_id = "<?php echo $desgination_id ?>";
        get_designation(d_id);
        if (d_id != "") {
            var ap_id = "<?php echo $approver_id ?>";
            if (ap_id != "") {
                get_approver(d_id, ap_id);
            }
        }

        // product cat
        get_product_cat();

        $(document).on('change', '.product_cat', function() {
            var cat_id = $(this).val();
            get_product_by_category_id(cat_id);
            $('.sample_type_category_quote').val(cat_id);

        })
        $(document).on('change', '.product', function() {
            var product_id = $(this).val();
            get_product_type(product_id);
        })

        $(document).on('change', '.customer', function() {
            var customer_id = $(this).val();
            var contact_id = "";
            $('.contacts_customer_id').val(customer_id);
            get_contact_by_customer_id(customer_id, contact_id);
        })

        $(document).on('change', '.country', function() {
            var country_id = $(this).val();
            get_state_by_country_id(country_id);
        })

        $(document).on('change', '.state', function() {
            var state_id = $(this).val();
            get_area_by_state_id(state_id);
        })


        $('.add_more_btn').on('click', function() {
            var customer_type = $('.customer_type').val();
            var customer_name = $(".customer option:selected").text();
            $('.contact_customer_type').val(customer_type);
            $('.contact_customer_name').val(customer_name);
            get_country();
        })

        $('.add_more_btn_cust').on('click', function() {

            var customer_type = $('.customer_type').val();
            $('.cust_customer_type').val(customer_type);
            get_country();
        })

        $('#add_contact_form').on('submit', function() {
            var customer_id = $('.contacts_customer_id').val();
            if (customer_id != "") {
                var contact_id = "";
                get_contact_by_customer_id(customer_id, contact_id);
            }

            submitContacts();

        })

        $('#add_customer_form').on('submit', function() {
            var type = $('.cust_customer_type').val();
            var customer_id = "";
            submitCustomers();
            get_customer_by_type(type, customer_id);
        })

        // manage details
        $(document).on('change', '.manageTest', function() {
            var value = $(this).val();
            if (value != "") {
                $('.add_test_button').attr('disabled', false);
                $('.add_test_button_division').attr('disabled', false);

            } else {
                $('.add_test_button').attr('disabled', true);
                $('.add_test_button_division').attr('disabled', true);
            }
        })

        $(document).on('click', '.add_test_button', function() {
            $(this).html("Loading...");
            $(this).attr("disabled", true);
            var test_ids = $('.manageTest').val();
            var currency_id = $('.currency').val();
            $('.test_submit').css("display", "block");
            get_test_container_window(test_ids, currency_id);
            $('.quote_window ').modal('hide');
        })

        $(document).on('click', '.add_test_button_division', function() {
            $(this).html("Loading...");
            $(this).attr("disabled", true);
            var test_id = $('.manageTest').val();
            var currency_id = $('.currency').val();
            var div_id = $('.manageTest').attr("data-id");
            var sample_type_id = $('.division_select_product').val();
            $('.test_submit').css("display", "block");
            get_test_container_window_division_wise(sample_type_id, currency_id, div_id, test_id);
            $('.quote_window ').modal('hide');

        })

        $(document).on('click', '.del_test', function() {


            count = $(this).attr("data-value");
            if (count != "") {
                for (i = 1; i < count; i++) {
                    $(this).parents("tr").next().remove();
                }

            }
            $(this).parents("tr").remove();
            update_quote_value();
            update_keys_of_test();
        })
        // date 22-03-2020 update

        function update_keys_of_test() {
            var table_rows = $('.test_table tbody tr');
            $.each(table_rows, function(row_index, rows_value) {

                var table_colums = $(rows_value).children("td");
                $.each(table_colums, function(col_index, col_val) {

                    if (col_index == 0) {
                        var inputs = $(col_val).children("input");
                        $(inputs[1]).attr("name", "test_data[" + row_index + "][test_division_id]");
                        $(inputs[2]).attr("name", "test_data[" + row_index + "][work_sample_type_id]");
                        $(inputs[3]).attr("name", "test_data[" + row_index + "][test_id]");
                        $(inputs[4]).attr("name", "test_data[" + row_index + "][work_id]");
                    }
                    if (col_index == 1) {
                        var inputs = $(col_val).children("input");
                        $(inputs[0]).attr("name", "test_data[" + row_index + "][test_method]");

                    }
                    if (col_index == 2) {
                        var inputs = $(col_val).children("input");
                        $(inputs[0]).attr("name", "test_data[" + row_index + "][work_division_name]");

                    }

                    if (col_index == 3) {
                        var inputs = $(col_val).children("input");
                        $(inputs[0]).attr("name", "test_data[" + row_index + "][price]");

                    }
                    if (col_index == 4) {
                        var inputs = $(col_val).children("input");
                        $(inputs[0]).attr("name", "test_data[" + row_index + "][discount]");

                    }

                    if (col_index == 5) {
                        var inputs = $(col_val).children("input");
                        $(inputs[0]).attr("name", "test_data[" + row_index + "][applicable_charge]");

                    }

                })


            })
        }
        // end



        // function reset_names(tableClass){
        //    var tableRows =  $('table.'+tableClass).children("tbody").children("tr");
        //     $.each(tableRows,function(index,value){
        //        column = $(value).children("td");
        //        $.each(column,function(key,name){
        //           input = $(name).children("input");
        //           $.each(input,function(input_key,input_names){
        //               $(input_names).attr('name');  
        //           })
        //        })

        //     })
        // }




        // get contacts

        function get_customer_by_type(type, cust_id) {

            const _tokken = $('meta[name="_tokken"]').attr('value');
            $.ajax({
                url: "<?php echo base_url('Quotes/get_customer_by_type') ?>",
                method: "post",
                data: {
                    _tokken: _tokken,
                    type: type
                },
                success: function(data) {
                    var data = $.parseJSON(data);
                    $('.customer').html("");
                    option = "<option value=''>Select Customer</option>";
                    noOpt = "<option value='' disabled>No Record Found</option>";
                    $('.customer').append(option);
                    if (data) {

                        $.each(data, (i, v) => {
                            if (v.customer_id == cust_id) {
                                option = "<option value='" + v.customer_id + "' selected>" + v.customer_name + "</option>";
                            } else {
                                option = "<option value='" + v.customer_id + "'>" + v.customer_name + "</option>";
                            }

                            $('.customer').append(option);
                        })
                    } else {
                        $('.customer').append(noOpt);
                    }
                }
            });
            return false;
        }

        // get customers

        function get_contact_by_customer_id(customer_id, contac_id) {
            const _tokken = $('meta[name="_tokken"]').attr('value');
            $.ajax({
                url: "<?php echo base_url('Quotes/get_contact_by_customer_id') ?>",
                method: "post",
                data: {
                    _tokken: _tokken,
                    customer_id: customer_id
                },
                success: function(data) {
                    var data = $.parseJSON(data);
                    $('.contacts').html("");
                    option = "<option value=''>Select Contact</option>";
                    noOpt = "<option value='' disabled>No Record Found</option>";
                    $('.contacts').append(option);
                    if (data) {
                        $.each(data, (i, v) => {
                            if (v.contact_id == contac_id) {
                                option = "<option value='" + v.contact_id + "' selected>" + v.contact_name + "</option>";
                            } else {
                                option = "<option value='" + v.contact_id + "'>" + v.contact_name + "</option>";
                            }

                            $('.contacts').append(option);
                        })
                    } else {
                        $('.contacts').append(noOpt);
                    }
                }
            });
            return false;
        }

        // get country

        function get_country() {
            const _tokken = $('meta[name="_tokken"]').attr('value');
            $.ajax({
                url: "<?php echo base_url('Quotes/get_country') ?>",
                method: "post",
                data: {
                    _tokken: _tokken,
                },
                success: function(data) {
                    var data = $.parseJSON(data);
                    $('.country').html("");
                    option = "<option value=''>Select Country</option>";
                    noOpt = "<option value='' disabled>No Record Found</option>";
                    $('.country').append(option);
                    if (data) {
                        $.each(data, (i, v) => {
                            option = "<option value='" + v.country_id + "'>" + v.country_name + "</option>";
                            $('.country').append(option);
                        })
                    } else {
                        $('.country').append(noOpt);
                    }
                }
            });
            return false;
        }

        // get state
        function get_state_by_country_id(country_id) {
            const _tokken = $('meta[name="_tokken"]').attr('value');
            $.ajax({
                url: "<?php echo base_url('Quotes/get_state_by_country_id') ?>",
                method: "post",
                data: {
                    _tokken: _tokken,
                    country_id: country_id
                },
                success: function(data) {
                    var data = $.parseJSON(data);
                    $('.state').html("");
                    option = "<option value=''>Select State</option>";
                    noOpt = "<option value='' disabled>No Record Found</option>";
                    $('.state').append(option);
                    if (data) {
                        $.each(data, (i, v) => {
                            option = "<option value='" + v.province_id + "'>" + v.province_name + "</option>";
                            $('.state').append(option);
                        })
                    } else {
                        $('.state').append(noOpt);
                    }
                }
            });
            return false;
        }

        // get area

        function get_area_by_state_id(state_id) {
            const _tokken = $('meta[name="_tokken"]').attr('value');
            $.ajax({
                url: "<?php echo base_url('Quotes/get_area_by_state_id') ?>",
                method: "post",
                data: {
                    _tokken: _tokken,
                    mst_locations_province_id: state_id
                },
                success: function(data) {
                    var data = $.parseJSON(data);
                    $('.area').html("");
                    option = "<option value=''>Select Area/Locations</option>";
                    noOpt = "<option value='' disabled>No Record Found</option>";
                    $('.area').append(option);
                    if (data) {
                        $.each(data, (i, v) => {
                            option = "<option value='" + v.location_id + "'>" + v.location_name + "</option>";
                            $('.area').append(option);
                        })
                    } else {
                        $('.area').append(noOpt);
                    }
                }
            });
            return false;
        }

        // get currency
        function get_currency(currency_id) {
            const _tokken = $('meta[name="_tokken"]').attr('value');
            $.ajax({
                url: "<?php echo base_url('Quotes/get_currency') ?>",
                method: "post",
                data: {
                    _tokken: _tokken,
                },
                success: function(data) {
                    var data = $.parseJSON(data);
                    $('.currency').html("");
                    option = "<option value=''>Select Currency</option>";
                    noOpt = "<option value='' disabled>No Record Found</option>";
                    $('.currency').append(option);
                    if (data) {
                        $.each(data, (i, v) => {
                            if (currency_id == v.currency_id) {
                                option = "<option value='" + v.currency_id + "' data-id='" + v.exchange_rate + "' data-code='" + v.currency_code + "' selected>" + v.currency_name + "</option>";
                                $('.ext_rate').val(v.exchange_rate);
                                $('.currency_code').val(v.currency_code);
                            } else {
                                option = "<option value='" + v.currency_id + "' data-id='" + v.exchange_rate + "' data-code='" + v.currency_code + "'>" + v.currency_name + "</option>";
                            }

                            $('.currency').append(option);
                        })


                    } else {
                        $('.currency').append(noOpt);
                    }
                }
            });
            return false;
        }
        // submit contacts

        function submitContacts() {
            $.ajax({
                url: "<?php echo base_url('Quotes/submit_contacts') ?>",
                method: "post",
                data: $('#add_contact_form').serialize(),
                success: function(data) {
                    var msg = $.parseJSON(data);
                    if (msg.status > 0) {
                        $.notify(msg.msg, 'success');
                        $('.add_more_contacts_popup').modal('hide');
                        $("#add_contact_form").trigger('reset');
                    } else {
                        $.notify(msg.msg, 'error');
                    }
                    if (msg.errors) {
                        var error = msg.errors;
                        $('.manage_contact_add').remove();
                        $.each(error, function(i, v) {
                            $('#add_contact_form input[name="' + i + '"]').after('<span class="text-danger manage_contact_add">' + v + '</span>');
                            $('#add_contact_form select[name="' + i + '"]').after('<span class="text-danger manage_contact_add">' + v + '</span>');
                        });

                    } else {
                        $('.manage_contact_add').remove();
                    }
                },
                error: function(e) {
                    console.log(e);
                }
            });
            return false;
        }

        // submit customers

        function submitCustomers() {
            $.ajax({
                url: "<?php echo base_url('Quotes/submit_customers') ?>",
                method: "post",
                data: $('#add_customer_form').serialize(),
                success: function(data) {
                    var msg = $.parseJSON(data);
                    if (msg.status > 0) {
                        $.notify(msg.msg, 'success');
                        $('.add_more_cust_popup').modal('hide');
                        $("#add_customer_form").trigger('reset');
                    } else {
                        $.notify(msg.msg, 'error');
                    }
                    if (msg.errors) {
                        var error = msg.errors;
                        $('.manage_cust_add').remove();
                        $.each(error, function(i, v) {
                            $('#add_customer_form input[name="' + i + '"]').after('<span class="text-danger manage_cust_add">' + v + '</span>');
                            $('#add_customer_form select[name="' + i + '"]').after('<span class="text-danger manage_cust_add">' + v + '</span>');
                            $('#add_customer_form textarea[name="' + i + '"]').after('<span class="text-danger manage_cust_add">' + v + '</span>');
                        });

                    } else {
                        $('.manage_cust_add').remove();
                    }
                }
            });
            return false;
        }

        // product cat

        function get_product_cat() {
            const _tokken = $('meta[name="_tokken"]').attr('value');
            $.ajax({
                url: "<?php echo base_url('Quotes/get_product_cat') ?>",
                method: "post",
                data: {
                    _tokken: _tokken,
                },
                success: function(data) {
                    var data = $.parseJSON(data);
                    $('.product_cat').html("");
                    option = "<option value=''>Select Category</option>";
                    noOpt = "<option value='' disabled>No Record Found</option>";
                    $('.product_cat').append(option);
                    if (data) {
                        $.each(data, (i, v) => {
                            option = "<option value='" + v.sample_category_id + "'>" + v.sample_category_name + "</option>";

                            $('.product_cat').append(option);
                        })
                    } else {
                        $('.product_cat').append(noOpt);
                    }
                }
            });
            return false;
        }

        // get products by cat

        function get_product_by_category_id(type_category_id) {
            const _tokken = $('meta[name="_tokken"]').attr('value');
            $.ajax({
                url: "<?php echo base_url('Quotes/get_product_by_category_id') ?>",
                method: "post",
                data: {
                    _tokken: _tokken,
                    type_category_id: type_category_id
                },
                success: function(data) {
                    var data = $.parseJSON(data);
                    $('.product').html("");
                    option = "<option value=''>Select Product</option>";
                    noOpt = "<option value='' disabled>No Record Found</option>";
                    $('.product').append(option);
                    if (data) {
                        $.each(data, (i, v) => {
                            option = "<option value='" + v.sample_type_id + "'>" + v.sample_type_name + "</option>";
                            $('.product').append(option);
                        })
                    } else {
                        $('.product').append(noOpt);
                    }
                }
            });
            return false;
        }

        // get product type
        function get_packagesProtocols(type, currency_id, sample_type_id) {

            if (type == 1) {
                name_key = 'package_data';
                var pre_row = $('.package_data tbody tr').length;
                if (pre_row > 0) {
                    pre_row += pre_row;
                }
                ID_name = 'package_id';


            }
            if (type == 2) {
                name_key = 'protocol_data';
                ID_name = 'protocol_id';
                var pre_row = $('.protocol_data tbody tr').length;
            }
            const _tokken = $('meta[name="_tokken"]').attr('value');
            $.ajax({
                url: "<?php echo base_url('Quotes/get_packagesProtocol') ?>",
                method: "post",
                data: {
                    _tokken: _tokken,
                    sample_type_id: sample_type_id,
                    currency_id: currency_id,
                    type: type
                },
                success: function(data) {
                    var data = $.parseJSON(data);
                    $('.managePackagesProtocol').html("");
                    option = "<option value=''>Select</option>";
                    noOpt = "<option value='' disabled>No Record Found</option>";
                    $('.managePackagesProtocol').append(option);
                    if (data) {


                        $.each(data, function(index, value) {
                            option = "<option value='" + value.id + "' >" + value.name + "</option>";
                            $('.managePackagesProtocol').append(option);



                        })
                    } else {
                        $('.managePackagesProtocol').append(noOpt);

                    }
                }
            });
            return false;
        }

        function get_product_type(product_id) {
            const _tokken = $('meta[name="_tokken"]').attr('value');
            $.ajax({
                url: "<?php echo base_url('Quotes/get_product_type') ?>",
                method: "post",
                data: {
                    _tokken: _tokken,
                    sample_type_id: product_id
                },
                success: function(data) {
                    var data = $.parseJSON(data);
                    $('.product_type').html("");
                    option = "<option value=''>Select Type</option>";
                    noOpt = "<option value='' disabled>No Record Found</option>";
                    $('.product_type').append(option);
                    if (data) {
                        $.each(data, (i, v) => {
                            option = "<option value='" + i + "'>" + v + "</option>";
                            $('.product_type').append(option);
                        })
                    } else {
                        $('.product_type').append(noOpt);
                    }
                }
            });
            return false;
        }

        // get test 
        var test_id_array = [];

        function get_tests(sample_type_id, currency_id) {

            const _tokken = $('meta[name="_tokken"]').attr('value');
            $.ajax({
                url: "<?php echo base_url('Quotes/get_tests') ?>",
                method: "post",
                data: {
                    sample_type_id: sample_type_id,
                    currency_id: currency_id,
                    _tokken: _tokken,
                },
                success: function(data) {
                    var data = $.parseJSON(data);

                    $('.manageTest').html("");
                    option = "<option value='' disabled>Select Tests</option>";
                    noOpt = "<option value='' disabled>No Record Found</option>";
                    $('.manageTest').append(option);
                    test_id_array = [];
                    if (data) {
                        $.each(data, (i, v) => {
                            option = "<option value='" + v.id + "'>" + v.name + "</option>";
                            $('.manageTest').append(option);
                            test_id_array.push(v.id);
                        })

                    } else {
                        $('.manageTest').append(noOpt);
                    }
                }
            });
            return false;
        }

        function get_tests_by_division(sample_type_id, currency_id, div_id) {

            const _tokken = $('meta[name="_tokken"]').attr('value');
            $.ajax({
                url: "<?php echo base_url('Quotes/get_tests_by_division') ?>",
                method: "post",
                data: {
                    sample_type_id: sample_type_id,
                    currency_id: currency_id,
                    div_id: div_id,
                    _tokken: _tokken,
                },
                success: function(data) {
                    var data = $.parseJSON(data);

                    $('.manageTest').html("");
                    option = "<option value='' disabled>Select Tests</option>";
                    noOpt = "<option value='' disabled>No Record Found</option>";
                    $('.manageTest').append(option);
                    test_id_array_division = [];
                    if (data) {
                        $.each(data, (i, v) => {
                            option = "<option data-id='" + v.test_division_id + "' value='" + v.test_id + "'>" + v.test_name + "</option>";
                            $('.manageTest').append(option);
                            test_id_array_division.push(v.test_division_id);
                        })

                    } else {
                        $('.manageTest').append(noOpt);
                    }
                }
            });
            return false;
        }

        $(document).on('change', '.select_all', function() {
            var currency_id = $('.currency').val();

            if (this.checked) {
                get_test_container_window(test_id_array, currency_id);
                $('.add_test_button').css("display", "none");
            } else {

                get_test_container_window([], currency_id);
                $('.add_test_button').css("display", "block");
            }
        })

        $(document).on('change', '.select_all_divi', function() {
            var currency_id = $('.currency').val();
            // var div_id = $('.manageTest').attr("data-id");
            var sample_type_id = $('.division_select_product').val();
            if (this.checked) {
                get_test_container_window_division_wise(sample_type_id, currency_id, test_id_array_division, null);
            } else {
                get_test_container_window_division_wise(sample_type_id, currency_id, [], null);
            }
        })

        // get test container window
        function get_test_container_window(test_ids = [], currency_id) {
            const _tokken = $('meta[name="_tokken"]').attr('value');
            $.ajax({
                url: "<?php echo base_url('Quotes/get_test_container_window') ?>",
                method: "post",
                data: {
                    test_ids: test_ids,
                    currency_id: currency_id,
                    _tokken: _tokken,
                },
                success: function(data) {
                    $('.add_test_button').html("ADD");
                    $('.add_test_button').attr("disabled", false);
                    $('.quote_window').modal('hide');
                    var data = $.parseJSON(data);
                    $('.test_container').html("");
                    table = "<table class='table test_table'>";
                    table += "<thead>";
                    table += "<tr>";
                    table += "<th>Test</th>";
                    table += "<th>Method</th>";
                    table += "<th>Division</th>";
                    table += "<th>Rate</th>";
                    table += "<th>Discount</th>";
                    table += "<th>Applicable Charge</th>";
                    table += "<th>Action</th>";
                    table += "</tr>";
                    table += "</thead>";
                    table += "<tbody>";
                    table += "</tbody>";
                    table += "</table>";
                    var delIcon = '<?php echo base_url('assets/images/del.png') ?>';
                    noOpt = "<tr><td colspan='7' disabled>No Record Found</td></tr>";
                    $('.test_container').append($(table));

                    var prior_row = $('.test_table tbody tr').length;
                    if (data) {
                        $.each(data, function(i, v) {

                            if (v.price == 'null' || v.price == null) {
                                price = 0;

                            } else {
                                var price = v.price;
                            }

                            var discount = 0;
                            var applicable_charge = price;
                            var sample_type_id_ = $('.product').val();
                            row = "<tr>";
                            row += "<td><input type='checkbox' class='test_list' value='" + v.id + "' data-sample_type_id='" + sample_type_id_ + "' data-price='" + applicable_charge + "'><input type='hidden' name ='test_data[" + (prior_row) + "][test_division_id]' value='" + v.test_division_id + "' class='div_id" + (prior_row) + "' data-type='0'><input type='hidden' name ='test_data[" + (prior_row) + "][work_sample_type_id]' value='" + sample_type_id_ + "' class='work_sample_type_id" + (prior_row) + "' data-type='0'><input type='hidden' name ='test_data[" + (prior_row) + "][test_id]' class='form-control form-control-sm test_id" + i + "' value='" + v.id + "' data-type='0'>" + v.name + "</td>";
                            row += "<td><input type='text' name ='test_data[" + (prior_row) + "][test_method]' class='form-control form-control-sm test_method" + (prior_row) + "' value='" + v.test_method + "' readonly data-type='0'></td>";
                            row += "<td><input type='text' name ='test_data[" + (prior_row) + "][work_division_name]' class='form-control form-control-sm work_div_name" + (prior_row) + "' value='" + v.work_division_name + "' readonly data-type='0'></td>";
                            row += "<td><input type='number' name ='test_data[" + (prior_row) + "][price]'  class='form-control form-control-sm rate price_test" + (prior_row) + "' value='" + price + "' readonly style='background-color:#B0B0B0' data-type='0'></td>";
                            row += "<td><input type='number' name ='test_data[" + (prior_row) + "][discount]' class='form-control form-control-sm discount discount_test" + (prior_row) + "' value='" + discount + "'  data-type='0' ></td>";
                            row += "<td><input type='number' name ='test_data[" + (prior_row) + "][applicable_charge]' class='form-control form-control-sm applicable_charge applicable_charge_test" + (prior_row) + "' value='" + applicable_charge + "' readonly data-type='0'></td>";
                            row += "<td><button type='button' data-value='' value='" + v.id + "' title='Remove' class='del_test btn btn-default btn-sm' data-id='" + (prior_row) + "'><img src='" + delIcon + "'></button></td>";

                            row += "</tr>";
                            $('.test_table tbody').append($(row));


                            prior_row++;

                        })

                    }
                    update_quote_value();
                }
            });
            return false;


        }

        // get test container window division wise
        function get_test_container_window_division_wise(sample_type_id, currency_id, div_id, test_id = null) {

            const _tokken = $('meta[name="_tokken"]').attr('value');
            $.ajax({
                url: "<?php echo base_url('Quotes/get_tests_by_division_window') ?>",
                method: "post",
                data: {
                    test_id: test_id,
                    sample_type_id: sample_type_id,
                    currency_id: currency_id,
                    div_id: div_id,
                    _tokken: _tokken,
                },
                success: function(data) {

                    $('.add_test_button_division').html("ADD");
                    $('.add_test_button_division').attr("disabled", false);


                    $('.quote_window').modal("hide");
                    var data = $.parseJSON(data);
                    $('.test_container').html("");
                    table = "<table class='table test_table'>";
                    table += "<thead>";
                    table += "<tr>";
                    table += "<th>Test</th>";
                    table += "<th>Method</th>";
                    table += "<th>Division</th>";
                    table += "<th>Rate</th>";
                    table += "<th>Discount</th>";
                    table += "<th>Applicable Charge</th>";
                    table += "<th>Action</th>";
                    table += "</tr>";
                    table += "</thead>";
                    table += "<tbody>";
                    table += "</tbody>";
                    table += "</table>";
                    var delIcon = '<?php echo base_url('assets/images/del.png') ?>';
                    noOpt = "<tr><td colspan='7' disabled>No Record Found</td></tr>";
                    $('.test_container').append($(table));
                    var prer_row = $('.test_table tbody tr').length;
                    // prer_row = 0;
                    if (data) {
                        $.each(data, function(i, v) {

                            if (v.price == 'null' || v.price == null) {
                                price = 0;

                            } else {
                                var price = v.price;
                            }

                            var discount = 0;
                            var applicable_charge = price;
                            var sample_type_id_ = $('.product').val();
                            row = "<tr>";
                            row += "<td><input type='hidden' name ='test_data[" + prer_row + "][test_division_id]' value='" + v.test_division_id + "' class='div_id" + prer_row + "' data-type='0'><input type='hidden' name ='test_data[" + prer_row + "][work_sample_type_id]' value='" + v.work_sample_type_id + "' class='work_sample_type_id" + prer_row + "' data-type='0'><input type='hidden' name ='test_data[" + prer_row + "][test_id]' class='form-control form-control-sm test_id" + i + "' value='" + v.test_id + "' data-type='0'>" + v.test_name + "</td>";
                            row += "<td><input type='text' name ='test_data[" + prer_row + "][test_method]' class='form-control form-control-sm test_method" + prer_row + "' value='" + v.test_method + "' readonly data-type='0'></td>";
                            row += "<td><input type='text' name ='test_data[" + prer_row + "][work_division_name]' class='form-control form-control-sm work_div_name" + prer_row + "' value='" + v.test_division_name + "' readonly data-type='0'></td>";
                            row += "<td><input type='number' name ='test_data[" + prer_row + "][price]'  class='form-control form-control-sm rate price_test" + prer_row + "' value='" + price + "' readonly style='background-color:#B0B0B0' data-type='0'></td>";
                            row += "<td><input type='number' name ='test_data[" + prer_row + "][discount]' class='form-control form-control-sm discount discount_test" + prer_row + "' value='" + discount + "'  data-type='0' readonly disabled></td>";
                            row += "<td><input type='number' name ='test_data[" + prer_row + "][applicable_charge]' class='form-control form-control-sm applicable_charge applicable_charge_test" + prer_row + "' value='" + applicable_charge + "' readonly data-type='0'></td>";
                            row += "<td><button type='button' data-value='' value='" + v.id + "' title='Remove' class='del_test btn btn-default btn-sm' data-id='" + prer_row + "'><img src='" + delIcon + "'></button></td>";

                            row += "</tr>";
                            $('.test_table tbody').append($(row));
                            prer_row++;

                        })

                    }

                    update_quote_value();
                }
            });
            return false;


        }

        function update_quote_value() {

            var quote_v = $('.applicable_charge');
            var sum_quote = 0;
            $.each(quote_v, function(index, value) {
                if (value.value == "") {
                    value.value = 0;
                } else {
                    value.value = value.value;
                }
                sum_quote += parseFloat(value.value);
            })
            $('.quote_value').val(sum_quote.toFixed(2));
        }

        // calculation
        $(document).on('change', '.discount', function() {
            var discount = $(this).val();
            var rate = $(this).parents("td").prev("td").children(".rate").val();
            var new_charge = rate - [(rate * discount) / 100];
            $(this).parents("td").next("td").children(".applicable_charge").val(new_charge.toFixed(2));
            update_quote_value();
        })

        $(document).on('change', '.applicable_charge', function() {
            var charge = $(this).val();
            $(this).parents("td").prev("td").prev("td").prev("td").prev("td").prev("td").children(".test_list").attr('data-price', charge);
            $(this).attr('value', charge);
            var rate = $(this).parents("td").prev("td").prev("td").children(".rate").val();

            if (charge >= rate) {

                $(this).parents("td").prev("td").prev("td").children(".rate").attr('value', charge);
                $(this).parents("td").prev("td").children(".discount").attr('value', 0);
                $(this).parents("td").prev("td").children(".discount").attr('min', 0);

            } else {

                var new_discount = [(rate - charge) * 100] / rate;
                if (new_discount > 0) {

                    $(this).parents("td").prev("td").children(".discount").attr('value', new_discount);
                } else {
                    $(this).parents("td").prev("td").children(".discount").attr('value', 0);
                }

            }
            update_quote_value();
        })

        $(document).on('dblclick', '.discount', function() {
            $(this).attr('readonly', false);
        })
        $(document).on('focusout', '.discount', function() {
            $(this).attr('readonly', true);
        })
        $(document).on('dblclick', '.applicable_charge', function() {
            $(this).attr('readonly', false);
        })
        $(document).on('focusout', '.applicable_charge', function() {
            $(this).attr('readonly', true);
        })

        // get_designation
        $(document).on('change', '.approver_dsg', function() {
            var des_id = $(this).val();
            var approver_id = "";
            get_approver(des_id, approver_id);
        })

        function get_designation(d_id) {
            const _tokken = $('meta[name="_tokken"]').attr('value');
            $.ajax({
                url: "<?php echo base_url('Quotes/designation') ?>",
                method: "post",
                data: {
                    _tokken: _tokken,
                },
                success: function(data) {
                    var data = $.parseJSON(data);
                    $('.approver_dsg').html("");
                    option = "<option value=''>Select </option>";
                    noOpt = "<option value='' disabled>No Record Found</option>";
                    $('.approver_dsg').append(option);
                    if (data) {
                        $.each(data, (i, v) => {
                            if (v.designation_id == d_id) {
                                option = "<option value='" + v.designation_id + "' selected>" + v.designation_name + "</option>";
                            } else {
                                option = "<option value='" + v.designation_id + "'>" + v.designation_name + "</option>";
                            }

                            $('.approver_dsg').append(option);
                        })
                    } else {
                        $('.approver_dsg').append(noOpt);
                    }
                }
            });
            return false;
        }

        function get_approver(des_id, approver_id) {
            const _tokken = $('meta[name="_tokken"]').attr('value');
            $.ajax({
                url: "<?php echo base_url('Quotes/get_approver') ?>",
                method: "post",
                data: {
                    _tokken: _tokken,
                    des_id: des_id
                },
                success: function(data) {
                    var data = $.parseJSON(data);
                    $('.approver').html("");
                    option = "<option value=''>Select </option>";
                    noOpt = "<option value='' disabled>No Record Found</option>";
                    $('.approver').append(option);
                    if (data) {
                        $.each(data, (i, v) => {
                            if (v.uidnr_admin == approver_id) {
                                option = "<option value='" + v.uidnr_admin + "' selected>" + v.name + "</option>";
                            } else {
                                option = "<option value='" + v.uidnr_admin + "'>" + v.name + "</option>";
                            }

                            $('.approver').append(option);
                        })
                    } else {
                        $('.approver').append(noOpt);
                    }
                }
            });
            return false;
        }

        // insert_quotes
        $('#quotes').submit(function(e) {
            $('#quotes_submit_button').html("Loading...");
            $('#quotes_submit_button').attr("disabled", true);
            $('.discount').attr("disabled", false);
            var quote_value = $('.quote_value').val();
            if (quote_value <= 0) {
                $.notify('Please select atleast one test value', 'error');
                return false;
            }
            e.preventDefault();
            var formData = new FormData(this);
            var quote_id = $('.quote_id').val();
            formData.append('quote_subject', CKEDITOR.instances['quote_subject'].getData());
            formData.append('salutation', CKEDITOR.instances['salutation'].getData());
            formData.append('terms_conditions', CKEDITOR.instances['terms_conditions'].getData());
            formData.append('sample_retention', CKEDITOR.instances['sample_retention'].getData());
            formData.append('payment_terms', CKEDITOR.instances['payment_terms'].getData());
            formData.append('additional_notes', CKEDITOR.instances['additional_notes'].getData());
            // formData.append('about_us_details', CKEDITOR.instances['about_us_details'].getData());
            formData.append('notes_details', CKEDITOR.instances['notes_details'].getData());
            formData.append('contact_details', CKEDITOR.instances['contact_details'].getData());
            if (quote_id == "") {
                url_ = "<?php echo base_url('Quotes/add_quote') ?>";
            } else {
                url_ = "<?php echo base_url('Quotes/update_quote') ?>";
            }
            $.ajax({
                url: url_,
                method: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    $('#quotes_submit_button').html("Submit");
                    $('#quotes_submit_button').attr("disabled", false);
                    var msg = $.parseJSON(data);

                    if (msg.status > 0) {
                        $.notify(msg.msg, 'success');
                        location.href = '<?php echo base_url("quotes") ?>';
                    } else {
                        $.notify(msg.msg, 'error');
                    }
                    if (msg.errors) {
                        var error = msg.errors;
                        $('.manage_quote_add').remove();
                        $.each(error, function(i, v) {

                            $('#quotes input[name="' + i + '"]').after('<span class="text-danger manage_quote_add">' + v + '</span>');
                            $('#quotes select[name="' + i + '"]').after('<span class="text-danger manage_quote_add">' + v + '</span>');
                            $('#quotes textarea[name="' + i + '"]').after('<span class="text-danger manage_quote_add">' + v + '</span>');
                        });

                    } else {
                        $('.manage_quote_add').remove();
                    }
                }
            });

        })

        // ADD TEST CODE START
        $(document).on('click', '.add_extra_test', function() {
            var manageDetails = $('.manage_details').html();
            $('.manage_details').html("");
            add_test_form = "";
            add_test_form += "<div class='row p-2'>";
            add_test_form += "<div class = 'col-sm-12'>";
            add_test_form += "<h3>ADD TEST</h3>";
            add_test_form += "</div>";
            add_test_form += "</div>";
            // first row
            add_test_form += "<form id='save_test_by_test' action='javascript:void(0);'>";

            add_test_form += "<input type='hidden' name='<?php echo $this->security->get_csrf_token_name(); ?>' value='<?php echo $this->security->get_csrf_hash(); ?>''>";

            add_test_form += "<input type='hidden' name='test_sample_type_id' value='' class='add_test_extra_product'>";

            add_test_form += "<div class='row p-2'>";

            add_test_form += "<div class = 'col-sm-3'>";
            add_test_form += "<lable>DIVISION</label>";
            add_test_form += "<select class='form-control form-control-sm add_test_division division_select' name='test_division_id' value='' ></select>";
            add_test_form += "</div>";

            add_test_form += "<div class = 'col-sm-3'>";
            add_test_form += "<lable>LAB TYPE</label>";
            add_test_form += "<select class='form-control form-control-sm add_test_lab_type' name='test_lab_type_id' value=''></select>";
            add_test_form += "</div>";

            add_test_form += "<div class = 'col-sm-3'>";
            add_test_form += "<lable>TEST NAME</label>";
            add_test_form += "<input type='text' class='form-control form-control-sm test_name_extra' name='test_name' placeholder='Type test name...'>";
            add_test_form += "</div>";

            add_test_form += "<div class = 'col-sm-3'>";
            add_test_form += "<lable>TEST  METHOD</label>";
            add_test_form += "<input type='text' class='form-control form-control-sm test_method_extra' name='test_method' placeholder='Type test method...'>";
            add_test_form += "</div>";

            add_test_form += "</div>";
            // end 

            // second row
            add_test_form += "<div class='row p-2'>";

            add_test_form += "<div class = 'col-sm-3'>";
            add_test_form += "<lable>MIN. SAMPLE QTY UNIT</label>";
            add_test_form += "<input type='number' class='form-control form-control-sm minimum_quantity_extra' name='minimum_quantity' placeholder='MIN. SAMPLE QTY UNIT...'>";
            add_test_form += "</div>";

            add_test_form += "<div class = 'col-sm-3'>";
            add_test_form += "<lable>UNITS</label>";
            add_test_form += "<select class='form-control form-control-sm units_extra' name='minimum_quantity_units' value=''></select>";
            add_test_form += "</div>";

            add_test_form += "<div class = 'col-sm-3'>";
            add_test_form += "<lable>REPORT UNIT</label>";
            add_test_form += "<select class='form-control form-control-sm report_units_extra' name='units' value=''></select>";
            add_test_form += "</div>";

            add_test_form += "<div class = 'col-sm-3'>";
            add_test_form += "<lable>SERVICE TYPE</label>";
            add_test_form += "<select class='form-control form-control-sm service_type_extra' name='test_service_type[]'  multiple><option value='Regular'>Regular</option><option value='Express'>Express</option><option value='Urgent'>Urgent</option></select>";
            add_test_form += "</div>";

            add_test_form += "</div>";
            // end 

            // third row
            add_test_form += "<div class='row p-2'>";

            add_test_form += "<div class='col-sm-3'>";
            add_test_form += "<lable>METHOD TYPE</label>";
            add_test_form += "<select class='form-control form-control-sm units_extra_method' name='method_type' value=''><option value='IHTM'>IN HOUSE</option><option value='SUB_CONTRACT'>SUB CONTRACT</option></select>";
            add_test_form += "</div>";

            add_test_form += "<div class='sub_contract_details col-sm-9'>";
            add_test_form += "</div>";

            add_test_form += "</div>";
            // end

            // forth row 
            add_test_form += "<div class='row mt-2 text-right p-3'>";
            add_test_form += "<div class = 'col-sm-12 px-3'>";
            add_test_form += "<button class='btn btn-sm btn-primary back_test_extra' type='button'>BACK</button>";
            add_test_form += "&nbsp&nbsp<button class='btn btn-sm btn-success save_test_extra' type='button' >SAVE</button>";
            add_test_form += "</div>";
            add_test_form += "</div>";

            add_test_form += "</form>";
            // end

            $('.manage_details').html(add_test_form);
            $('.service_type_extra').select2({
                placeholder: "Select service type..."
            });


            load_division();
            load_lab_types();
            load_units();
            load_Report_units();

            sample_type_id = $('.product').val();
            $('.add_test_extra_product').val(sample_type_id);

            $(document).on('change', '.product', function() {
                $('.add_test_extra_product').val($(this).val());
            })

            $(document).on('change', '.units_extra_method', function() {
                var method_type = $(this).val();
                if (method_type == 'SUB_CONTRACT') {
                    sub_con_detail = "";
                    sub_con_detail += "<div class = 'row'>";
                    sub_con_detail += "<div class = 'col-sm-4'>";
                    sub_con_detail += "<lable>SUB-CONTRACT LAB NAME</label>";
                    sub_con_detail += "<input type='text' class='form-control form-control-sm sub_con_lab' name='sub_contract[sub_contract_lab_name]' value='' placeholder=''></input>";
                    sub_con_detail += "</div>";

                    sub_con_detail += "<div class = 'col-sm-4'>";
                    sub_con_detail += "<lable>ADDRESS</label>";
                    sub_con_detail += "<textarea type='text' class='form-control form-control-sm lab_address' name='sub_contract[lab_address]' ></textarea>";
                    sub_con_detail += "</div>";

                    sub_con_detail += "<div class = 'col-sm-4'>";
                    sub_con_detail += "<lable>PRICE</label>";
                    sub_con_detail += "<input type='number' class='form-control form-control-sm test_price' name='sub_contract[test_price]' placeholder='ENTER PRICE...'>";
                    sub_con_detail += "</div>";
                    sub_con_detail += "</div>";
                    $('.sub_contract_details').html("");
                    $('.sub_contract_details').html(sub_con_detail);
                } else {
                    $('.sub_contract_details').html("");
                }

            })

            $(document).on('click', '.back_test_extra', function() {
                getTestBOX(manageDetails);
            })



            $(document).on('click', '.save_test_extra', function() {
                save_tests(manageDetails);
            })

        })

        function getTestBOX(Html) {
            $('.manage_details').html("");
            $('.manage_details').html(Html);
            sample_type_id = $('.product').val();
            currency_id = $('.currency').val();
            get_tests(sample_type_id, currency_id);
        }

        function save_tests(html) {
            $.ajax({
                url: "<?php echo base_url('Quotes/save_tests') ?>",
                method: "POST",
                data: $('#save_test_by_test').serialize(),
                success: function(response) {
                    var data = $.parseJSON(response);
                    if (data.status > 0) {
                        $.notify(data.msg, 'success');
                        getTestBOX(html);
                        var sample_type_id = $('.product').val();
                        var currency_id = $('.currency').val();
                        get_tests(sample_type_id, currency_id);
                    } else {
                        $.notify(data.msg, 'error')
                    }

                    if (data.errors) {
                        var error = data.errors;
                        $('.quote_error_add').remove();
                        $.each(error, function(i, v) {

                            $('#save_test_by_test input[name="' + i + '"]').after('<span class="text-danger quote_error_add">' + v + '</span>');
                            $('#save_test_by_test select[name="' + i + '"]').after('<span class="text-danger quote_error_add">' + v + '</span>');
                            $('#save_test_by_test textarea[name="' + i + '"]').after('<span class="text-danger quote_error_add">' + v + '</span>');
                        })

                    } else {
                        $('.quote_error_add').remove();
                    }
                }
            })

            return false;
        }

        function save_tests_by_division(html) {

            $.ajax({
                url: "<?php echo base_url('Quotes/save_tests') ?>",
                method: "POST",
                data: $('#save_test_by_division').serialize(),
                success: function(response) {
                    var data = $.parseJSON(response);
                    if (data.status > 0) {
                        $.notify(data.msg, 'success');
                        getTestBOX(html);
                    } else {
                        $.notify(data.msg, 'error')
                    }

                    if (data.errors) {
                        var error = data.errors;
                        $('.quote_error_add').remove();
                        $.each(error, function(i, v) {

                            $('#save_test_by_division input[name="' + i + '"]').after('<span class="text-danger quote_error_add">' + v + '</span>');
                            $('#save_test_by_division select[name="' + i + '"]').after('<span class="text-danger quote_error_add">' + v + '</span>');
                            $('#save_test_by_division textarea[name="' + i + '"]').after('<span class="text-danger quote_error_add">' + v + '</span>');
                        })

                    } else {
                        $('.quote_error_add').remove();
                    }
                }
            });
            xhr.abort();
            return false;
        }

        function load_lab_types(selected = null) {
            const _tokken = $('meta[name="_tokken"]').attr("value");
            $.ajax({
                url: "<?php echo base_url('Quotes/load_lab_types') ?>",
                method: "GET",
                success: function(response) {
                    var data = $.parseJSON(response);
                    $('.add_test_lab_type').html("");
                    option = "<option selected disabled>SELECT LAB TYPE</option>";
                    $('.add_test_lab_type').append(option);
                    $.each(data, function(index, value) {
                        if (selected) {
                            option = '<option value="' + value.lab_id + '" selected>' + value.lab_name + '</option>';
                        } else {
                            option = '<option value="' + value.lab_id + '">' + value.lab_name + '</option>';
                        }
                        $('.add_test_lab_type').append(option);


                    })
                }
            });
            return false;
        }

        function load_units(selected = null) {
            const _tokken = $('meta[name="_tokken"]').attr("value");
            $.ajax({
                url: "<?php echo base_url('Quotes/load_units') ?>",
                method: "GET",
                success: function(response) {
                    var data = $.parseJSON(response);
                    $('.units_extra').html("");
                    option = "<option selected disabled>SELECT UNITS</option>";
                    $('.units_extra').append(option);
                    $.each(data, function(index, value) {
                        if (selected) {
                            option = '<option value="' + value.unit_id + '" selected>' + value.unit_name + '</option>';
                        } else {
                            option = '<option value="' + value.unit_id + '">' + value.unit_name + '</option>';
                        }
                        $('.units_extra').append(option);


                    })
                }
            });
            return false;
        }

        function load_Report_units(selected = null) {
            const _tokken = $('meta[name="_tokken"]').attr("value");
            $.ajax({
                url: "<?php echo base_url('Quotes/load_units') ?>",
                method: "GET",
                success: function(response) {
                    var data = $.parseJSON(response);
                    $('.report_units_extra').html("");
                    option = "<option selected disabled>SELECT REPORT UNIT</option>";
                    $('.report_units_extra').append(option);
                    $.each(data, function(index, value) {
                        if (selected) {
                            option = '<option value="' + value.unit_id + '" selected>' + value.unit_name + '</option>';
                        } else {
                            option = '<option value="' + value.unit_id + '">' + value.unit_name + '</option>';
                        }
                        $('.report_units_extra').append(option);


                    })
                }
            });
            return false;
        }
        // CODE END

        // add test division wise more

        $(document).on('click', '.add_extra_test_division', function() {
            var manageDetails = $('.manage_details').html();
            $('.manage_details').html("");

            add_test_form = "";
            add_test_form += "<div class='row p-2'>";
            add_test_form += "<div class = 'col-sm-12'>";
            add_test_form += "<h3>ADD TEST</h3>";
            add_test_form += "</div>";
            add_test_form += "</div>";

            // first row

            add_test_form += "<form id='save_test_by_division' action='javascript:void(0);'>";

            add_test_form += "<input type='hidden' name='<?php echo $this->security->get_csrf_token_name(); ?>' value='<?php echo $this->security->get_csrf_hash(); ?>''>";

            add_test_form += "<div class='row p-2'>";

            add_test_form += "<div class = 'col-sm-3'>";
            add_test_form += "<lable>DIVISION</label>";
            add_test_form += "<select class='form-control form-control-sm add_test_division division_select' name='test_division_id' value='' ></select>";
            add_test_form += "</div>";

            add_test_form += "<div class = 'col-sm-3'>";
            add_test_form += "<lable>LAB TYPE</label>";
            add_test_form += "<select class='form-control form-control-sm add_test_lab_type' name='test_lab_type_id' value=''></select>";
            add_test_form += "</div>";

            add_test_form += "<div class = 'col-sm-3'>";
            add_test_form += "<lable>TEST NAME</label>";
            add_test_form += "<input type='text' class='form-control form-control-sm test_name_extra' name='test_name' placeholder='Type test name...'>";
            add_test_form += "</div>";

            add_test_form += "<div class = 'col-sm-3'>";
            add_test_form += "<lable>TEST  METHOD</label>";
            add_test_form += "<input type='text' class='form-control form-control-sm test_method_extra' name='test_method' placeholder='Type test method...'>";
            add_test_form += "</div>";

            add_test_form += "</div>";
            // end 

            // second row
            add_test_form += "<div class='row p-2'>";

            add_test_form += "<div class = 'col-sm-3'>";
            add_test_form += "<lable>MIN. SAMPLE QTY UNIT</label>";
            add_test_form += "<input type='number' class='form-control form-control-sm minimum_quantity_extra' name='minimum_quantity' placeholder='MIN. SAMPLE QTY UNIT...'>";
            add_test_form += "</div>";

            add_test_form += "<div class = 'col-sm-3'>";
            add_test_form += "<lable>UNITS</label>";
            add_test_form += "<select class='form-control form-control-sm units_extra' name='minimum_quantity_units' value=''></select>";
            add_test_form += "</div>";

            add_test_form += "<div class = 'col-sm-3'>";
            add_test_form += "<lable>REPORT UNIT</label>";
            add_test_form += "<select class='form-control form-control-sm report_units_extra' name='units' value=''></select>";
            add_test_form += "</div>";

            add_test_form += "<div class = 'col-sm-3'>";
            add_test_form += "<lable>SERVICE TYPE</label>";
            add_test_form += "<select class='form-control form-control-sm service_type_extra' name='test_service_type[]'  multiple><option value='Regular'>Regular</option><option value='Express'>Express</option><option value='Urgent'>Urgent</option></select>";
            add_test_form += "</div>";

            add_test_form += "</div>";
            // end 

            // row start
            add_test_form += "<div class='row p-2'>";

            add_test_form += "<div class = 'col-sm-3'>";
            add_test_form += "<lable>PRODUCT</label>";
            add_test_form += "<select class='form-control form-control-sm product_test_division' name='test_sample_type_id' value=''></select>";
            add_test_form += "</div>";


            add_test_form += "<div class='col-sm-3'>";
            add_test_form += "<lable>METHOD TYPE</label>";
            add_test_form += "<select class='form-control form-control-sm units_extra_method_div' name='method_type' value=''><option value='IHTM'>IN HOUSE</option><option value='SUB_CONTRACT'>SUB CONTRACT</option></select>";
            add_test_form += "</div>";

            add_test_form += "</div>";

            add_test_form += "<div class='sub_contract_details_div col-sm-12'>";
            add_test_form += "</div>";

            // end

            // third row 
            add_test_form += "<div class='row mt-2 text-right p-3'>";
            add_test_form += "<div class = 'col-sm-12 px-3'>";
            add_test_form += "<button class='btn btn-sm btn-primary back_test_extra_division' type='button'>BACK</button>";
            add_test_form += "&nbsp&nbsp<button class='btn btn-sm btn-success save_test_extra_division' type='button' >SAVE</button>";
            add_test_form += "</div>";
            add_test_form += "</div>";

            add_test_form += "</form>";
            // end

            $('.manage_details').html(add_test_form);
            $('.service_type_extra').select2({
                placeholder: "Select service type..."
            });

            load_division();
            load_lab_types();
            load_units();
            load_Report_units();
            load_products();

            $(document).on('click', '.back_test_extra_division', function() {
                getTestBOX(manageDetails);
            })
            $(document).on('click', '.save_test_extra_division', function() {
                save_tests_by_division(manageDetails);
            })
        })

        function load_products(selected = null) {
            const _tokken = $('meta[name="_tokken"]').attr("value");
            $.ajax({
                url: "<?php echo base_url('Quotes/load_products') ?>",
                method: "GET",
                success: function(response) {
                    var data = $.parseJSON(response);
                    $('.product_test_division').html("");
                    option = "<option selected disabled>SELECT PRODUCT</option>";
                    $('.product_test_division').append(option);
                    $.each(data, function(index, value) {
                        if (selected) {
                            option = '<option value="' + value.id + '" selected>' + value.name + '</option>';
                        } else {
                            option = '<option value="' + value.id + '">' + value.name + '</option>';
                        }
                        $('.product_test_division').append(option);
                    })
                }
            });
            return false;
        }

        $(document).on('change', '.units_extra_method_div', function() {
            var method_type = $(this).val();
            if (method_type == 'SUB_CONTRACT') {
                sub_con_detail = "";
                sub_con_detail += "<div class = 'row'>";
                sub_con_detail += "<div class = 'col-sm-4'>";
                sub_con_detail += "<lable>SUB-CONTRACT LAB NAME</label>";
                sub_con_detail += "<input type='text' class='form-control form-control-sm sub_con_lab_div' name='sub_contract[sub_contract_lab_name]' value='' placeholder=''></input>";
                sub_con_detail += "</div>";

                sub_con_detail += "<div class = 'col-sm-4'>";
                sub_con_detail += "<lable>ADDRESS</label>";
                sub_con_detail += "<textarea type='text' class='form-control form-control-sm lab_address_div' name='sub_contract[lab_address]' ></textarea>";
                sub_con_detail += "</div>";

                sub_con_detail += "<div class = 'col-sm-4'>";
                sub_con_detail += "<lable>PRICE</label>";
                sub_con_detail += "<input type='number' class='form-control form-control-sm test_price_div' name='sub_contract[test_price]' placeholder='ENTER PRICE...'>";
                sub_con_detail += "</div>";
                sub_con_detail += "</div>";
                $('.sub_contract_details_div').html("");
                $('.sub_contract_details_div').html(sub_con_detail);
            } else {
                $('.sub_contract_details_div').html("");
            }

        })

        var branch_id = "<?php echo $branch_id; ?>";
        load_branches(branch_id);

        function load_branches(branch_id = null) {
            $.ajax({
                url: "<?php echo base_url('Quotes/load_branches') ?>",
                method: "GET",
                success: function(response) {
                    var html = $.parseJSON(response);
                    $('.quotes_branch_dropdown').html("");
                    var option = '<option value="">Select branch</option>';
                    $('.quotes_branch_dropdown').append(option);
                    if (html) {
                        $.each(html, function(index, value) {
                            if (value.branch_id == branch_id) {
                                var option = '<option value="' + value.branch_id + '" selected>' + value.branch_name + '</option>';
                            } else {
                                var option = '<option value="' + value.branch_id + '">' + value.branch_name + '</option>';
                            }
                            $('.quotes_branch_dropdown').append(option);
                        })
                    } else {
                        var option = '<option value="" disabled>NO RECORD FOUND</option>';
                        $('.quotes_branch_dropdown').append(option);
                    }
                }
            })
        }

        function add_discount_division_wise_dropdown(selected = null) {
            const _tokken = $('meta[name="_tokken"]').attr("value");
            $.ajax({
                url: "<?php echo base_url('Quotes/load_divisions') ?>",
                method: "GET",
                success: function(response) {
                    var data = $.parseJSON(response);
                    $('.divsion_wise_discount').html("");
                    option = "<option value='' selected>Division wise discount</option>";
                    $('.divsion_wise_discount').append(option);
                    $.each(data, function(index, value) {
                        if (selected) {
                            option = '<option value="' + value.division_name + '" selected>' + value.division_name + '</option>';
                        } else {
                            option = '<option value="' + value.division_name + '">' + value.division_name + '</option>';
                        }
                        $('.divsion_wise_discount').append(option);
                    })
                }
            });
            return false;
        }

        $('.add_division_discounts').on('click', function() {
            var div_value = $('.division_discount').val();
            var rows = $('.test_table tbody tr');
            console.log(div_value);
            $.each(rows, function(i, v) {
                column = $(v).children("td")[2];
                input = $(column).children("input")[0];
                // if(div_value != ''){
                //     console.log('normal');
                //     if ($(input).val() == div_value) {
                //     discount_coulum = $(v).children("td")[4];
                //     discount_input = $(discount_coulum).children("input")[0];
                //     $(discount_input).attr('value', $('.division_discount').val());
                //     discount = $(discount_input).val();
                //     rate_column = $(v).children("td")[3];
                //     rate_input = $(rate_column).children("input")[0];
                //     rate = $(rate_input).val();
                //     new_charge = rate - [(rate * discount) / 100];
                //     chargee_col = $(v).children("td")[5];
                //     chargee_input = $(chargee_col).children("input")[0];
                //     $(chargee_input).attr('value', new_charge);
                //     $(chargee_input).val(new_charge);
                //     }
                // } else {
                discount_coulum = $(v).children("td")[4];
                discount_input = $(discount_coulum).children("input")[0];
                $(discount_input).attr('value', $('.division_discount').val());
                discount = $(discount_input).val();
                rate_column = $(v).children("td")[3];
                rate_input = $(rate_column).children("input")[0];
                rate = $(rate_input).val();
                new_charge = rate - [(rate * discount) / 100];
                chargee_col = $(v).children("td")[5];
                chargee_input = $(chargee_col).children("input")[0];
                $(chargee_input).attr('value', new_charge);
                $(chargee_input).val(new_charge);
                // }

            });
            update_quote_value();
        })

        $('.detail_quote_btn').on('click', function() {
            $('.quotes_details').html("");
            $('.manage_details').html("");
            $("#details_quote_reset").trigger('reset');
            $('.division_select').empty();
            $('.division_select_product').empty();
            $('.division_select_test').empty();
            $('.manageTest').empty();
        })

        $(document).on('change', '.managePackagesProtocol', function() {

            var product_type = $('.product_type').val();
            if (product_type == 1) {
                name_key = 'package_data';
                var pre_row = $('.package_data tbody tr').length;
                if (pre_row > 0) {
                    pre_row += pre_row;
                }
                ID_name = 'package_id';


            }
            if (product_type == 2) {
                name_key = 'protocol_data';
                ID_name = 'protocol_id';
                var pre_row = $('.protocol_data tbody tr').length;
            }
            var id = $(this).val();
            var price = $(this).data("id");
            var sample_type_id = $('.product').val();
            var currency_id = $('.currency').val();
            get_package_protocol_data_testing(id, currency_id, product_type, ID_name, pre_row, name_key, sample_type_id)
            // const _tokken = $('meta[name="_tokken"]').attr('value');
            // $.ajax({
            //     url: "<?php echo base_url('Quotes/get_package_protocol_data_testing') ?>",
            //     method: "post",
            //     data: {
            //         id: id,
            //         currency_id: currency_id,
            //         type: product_type,
            //         _tokken: _tokken
            //     },
            //     success: function(data) {
            //         data = $.parseJSON(data);
            //         $('.test_container').html("");
            //         table = "<table class='table test_table'>";
            //         table += "<thead>";
            //         table += "<tr>";
            //         table += "<td><b>Selected Test</b></td>";
            //         table += "<td><b>Test Method</b></td>";
            //         table += "<td><b>Test Division</b></td>";
            //         table += "</tr>";

            //         table += "</thead>";
            //         table += "<tbody>";
            //         table += "</tbody>";
            //         table += "</table>";
            //         var delIcon = '<?php echo base_url('assets/images/del.png') ?>';
            //         noOpt = "<tr><td colspan='7' disabled>No Record Found</td></tr>";
            //         $('.test_container').append(table);


            //         pre_row = 0;
            //         $.each(data, function(index1, value) {
            //             if (value.test_price == 'null' || value.test_price == null) {
            //                 price = 0;

            //             } else {
            //                 price = value.test_price;
            //             }
            //             $('.price_packagesProto').val(price);
            //             $('.charge_packagesProto').val(price);



            //             var row = "<tr>";
            //             row += "<td><input type='hidden' name ='" + name_key + "[" + pre_row + "][test_division_id]' value='" + value.test_division_id + "' class='div_id" + index1 + "' data-type='" + product_type + "'><input type='hidden' name ='" + name_key + "[" + pre_row + "][works_sample_type_id]' value='" + sample_type_id + "' class='work_sample_type_id" + pre_row + "' data-type='" + product_type + "'><input type='hidden' name ='" + name_key + "[" + pre_row + "][" + ID_name + "]' value='" + value.package_id + "' class='test_package_id" + pre_row + "' data-type='" + product_type + "'><input type='hidden' name ='" + name_key + "[" + pre_row + "][test_id]' class='form-control form-control-sm test_id" + pre_row + "' value='" + value.test_id + "' data-type='" + product_type + "' >" + value.test_name + "</td>";

            //             row += "<td><input type='text' name ='" + name_key + "[" + pre_row + "][test_method]' class='form-control form-control-sm test_method" + pre_row + "' value='" + value.test_method + "' readonly data-type='" + product_type + "'></td>";

            //             row += "<td><input type='text' name ='" + name_key + "[" + pre_row + "][work_division_name]' class='form-control form-control-sm work_div_name" + pre_row + "' value='" + value.work_division_name + "' readonly data-type='" + product_type + "'></td>";


            //             if (index1 == 0) {
            //                 row += "<td><input type='number' name ='" + name_key + "[" + pre_row + "][price]'  class='form-control form-control-sm rate price_test" + pre_row + "' value='" + price + "' readonly style='background-color:#B0B0B0;' data-type='" + product_type + "' ></td>";

            //                 row += "<td><input type='number' name ='" + name_key + "[" + pre_row + "][discount]' class='form-control form-control-sm  discount discount_test" + pre_row + "' value='0'  data-type='" + product_type + "' readonly></td>";

            //                 row += "<td><input type='number' name ='" + name_key + "[" + pre_row + "][total_cost]' class='form-control form-control-sm applicable_charge applicable_charge_test" + pre_row + "' value='" + price + "'  data-type='" + product_type + "'></td>";

            //                 row += "<td><button type='button' value='" + value.test_id + "' title='Remove' class='del_test btn btn-default btn-sm' data-value='' data-id='" + pre_row + "'><img src='" + delIcon + "'></button></td>";

            //             }
            //             row += "</tr>";

            //             $('.test_container table tbody').append(row);

            //             var rowsapan = $('.test_container table tbody tr').length;
            //             $('.del_test').attr("data-value", rowsapan);
            //             $('.discount_test0').attr('disabled', true);
            //             $(document).on('keyup change', '.discount_packagesProto', function() {
            //                 var app_charge = 0;
            //                 var disc = $(this).val();
            //                 var price = $('.price_packagesProto').val();

            //                 app_charge = (value.price) - [(value.price) / 100] * disc;

            //                 $('.charge_packagesProto').attr('value', app_charge.toFixed(2));
            //                 $('.discount_test0').attr('value', disc);
            //                 $('.applicable_charge_test0').attr('value', app_charge.toFixed(2));
            //             })
            //             pre_row++;
            //         })
            //     }
            // });
            return false;
        });

        $(document).on('click', '#add_package', function() {
            const _tokken = $('meta[name="_tokken"]').attr('value');
            var test_ids = '';
            var product_id = '';
            var test_prices = '';
            var to_add = $('#type_to_create').val();
            var currency_id = $('.currency').val();
            if (to_add == 1) {
                name_key = 'package_data';
                var pre_row = $('.package_data tbody tr').length;
                if (pre_row > 0) {
                    pre_row += pre_row;
                }
                ID_name = 'package_id';
            }
            if (to_add == 2) {
                name_key = 'protocol_data';
                ID_name = 'protocol_id';
                var pre_row = $('.protocol_data tbody tr').length;
            }
            $(".test_table input[type=checkbox]:checked").each(function() {
                var self = $(this);
                product_id = self.data('sample_type_id');
                if (test_ids == '') {
                    test_ids = self.val();
                } else {
                    test_ids = test_ids + ',' + self.val();
                }
                if (test_prices == '') {
                    test_prices = self.data('price');
                } else {
                    test_prices = test_prices + ',' + self.data('price');
                }
            });
            var protocol_name = $('.package_name').val();
            var protocol_price = $('.package_price').val();
            if (test_ids != '') {

                $.ajax({
                    type: 'post',
                    url: '<?php echo base_url('Quotes/save_package_protocol'); ?>',
                    data: {
                        test_ids: test_ids,
                        protocol_name: protocol_name,
                        protocol_price: protocol_price,
                        to_add: to_add,
                        product_id: product_id,
                        currency_id: currency_id,
                        test_prices: test_prices,
                        _tokken: _tokken
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data.status > 0) {
                            $.notify(data.message, 'success');

                            get_protocol_package_data(data.package_id, currency_id, to_add, ID_name, pre_row, name_key, product_id);
                            $(".test_table input[type=checkbox]:checked").each(function() {
                                var self = $(this);
                                self.parents('tr').remove();
                            });
                        } else {
                            $.notify(data.message, 'error');
                        }
                    }
                });
            } else {
                $.notify('Please select tests!.', 'error');
            }
        });

        function get_protocol_package_data(package_id, currency_id, product_type, ID_name, pre_row, name_key, sample_type_id) {
            const _tokken = $('meta[name="_tokken"]').attr("value");
            var delIcon = '<?php echo base_url('assets/images/del.png') ?>';
            $.ajax({
                type: 'post',
                url: '<?php echo base_url('Quotes/get_package_protocol_data_testing'); ?>',
                data: {
                    _tokken: _tokken,
                    id: package_id,
                    type:product_type,
                    currency_id:currency_id,
                    sample_type_id:sample_type_id
                },
                dataType: 'json',
                success: function(data) {
                    $.each(data, function(index1, value) {
                        if (value.test_price == 'null' || value.test_price == null) {
                            price = 0;
                        } else {
                            price = value.test_price;
                        }
                        $('.price_packagesProto').val(price);
                        $('.charge_packagesProto').val(price);
                        var row = "<tr>";
                        row += "<td><input type='hidden' name ='" + name_key + "[" + pre_row + "][test_division_id]' value='" + value.test_division_id + "' class='div_id" + index1 + "' data-type='" + product_type + "'><input type='hidden' name ='" + name_key + "[" + pre_row + "][works_sample_type_id]' value='" + sample_type_id + "' class='work_sample_type_id" + pre_row + "' data-type='" + product_type + "'><input type='hidden' name ='" + name_key + "[" + pre_row + "][" + ID_name + "]' value='" + value.package_id + "' class='test_package_id" + pre_row + "' data-type='" + product_type + "'><input type='hidden' name ='" + name_key + "[" + pre_row + "][test_id]' class='form-control form-control-sm test_id" + pre_row + "' value='" + value.test_id + "' data-type='" + product_type + "' >" + value.test_name + "</td>";
                        row += "<td><input type='text' name ='" + name_key + "[" + pre_row + "][test_method]' class='form-control form-control-sm test_method" + pre_row + "' value='" + value.test_method + "' readonly data-type='" + product_type + "'></td>";
                        row += "<td><input type='text' name ='" + name_key + "[" + pre_row + "][work_division_name]' class='form-control form-control-sm work_div_name" + pre_row + "' value='" + value.work_division_name + "' readonly data-type='" + product_type + "'></td>";
                        if (index1 == 0) {
                            row += "<td><input type='number' name ='" + name_key + "[" + pre_row + "][price]'  class='form-control form-control-sm rate price_test" + pre_row + "' value='" + price + "' readonly style='background-color:#B0B0B0;' data-type='" + product_type + "' ></td>";
                            row += "<td><input type='number' name ='" + name_key + "[" + pre_row + "][discount]' class='form-control form-control-sm  discount discount_test" + pre_row + "' value='0'  data-type='" + product_type + "' readonly></td>";
                            row += "<td><input type='number' name ='" + name_key + "[" + pre_row + "][total_cost]' class='form-control form-control-sm applicable_charge applicable_charge_test" + pre_row + "' value='" + price + "'  data-type='" + product_type + "'></td>";
                            row += "<td><button type='button' value='" + value.test_id + "' title='Remove' class='del_test btn btn-default btn-sm' data-value='' data-id='" + pre_row + "'><img src='" + delIcon + "'></button></td>";
                        }
                        row += "</tr>";
                        if(product_type == 1){
                            var c = $('.quotes_details_table table.package_data tbody');
                        } else {
                            var c = $('.quotes_details_table table.protocol_data tbody');
                        }
                        pre_row++;
                        c.append(row);
                    });
                    update_quote_value();
                    update_keys_of_test();
                    $('#make_package').val(0);
                    $('.package_name').val('');
                    $('.package_price').val(0);
                    $('#type_to_create option:selected').remove();
                    $('#choose_type').css('display','none');
                    $('#package_name').css('display','none');
                    $('#package_price').css('display','none');
                    $('#add_package_btn').css('display','none');
                }
            });

        }

        $(document).ready(function() {

            $('#make_package').change(function() {
                var option = $('#make_package').val();
                if (option == 1) {
                    $('#package_name').css('display', 'block');
                    $('#package_price').css('display', 'block');
                    $('#add_package_btn').css('display', 'block');
                    $('#choose_type').css('display', 'block');
                }
                if (option == 0) {
                    $('#package_name').css('display', 'none');
                    $('#package_price').css('display', 'none');
                    $('#add_package_btn').css('display', 'none');
                    $('#choose_type').css('display', 'none');
                }
            });


            $('.discount').attr("min", 0);

            $('input').attr('autocomplete', 'off');

            $('.customer').select2();
            // $('.quotes_branch_dropdown').select2();
            $('.customer_type').select2();
            $('.contacts').select2();
            $('.currency').select2();
            $('.approver_dsg').select2();
            $('.approver').select2();



            var currency_id = "<?php echo $currency_id; ?>";
            if (currency_id) {
                $('.detail_quote_btn').attr('disabled', false);

            }
            $('.currency').on('change', function() {
                var currency_id = $(this).val();
                if (currency_id) {
                    $('.detail_quote_btn').attr('disabled', false);

                } else {
                    $('.detail_quote_btn').attr('disabled', true);
                }
            })


            $('.delete_test_all_checkbox').on('change', function() {
                if (this.checked) {
                    $('.delete_tests').attr('disabled', false);
                } else {
                    $('.delete_tests').attr('disabled', true);
                }
            });

            $('.delete_tests').on('click', function() {

                $('.test_table tbody').html("");
                $('.package_data tbody').html("");
                $('.protocol_data tbody').html("");
                $('.quote_value').attr('value', 0.00);
                $('.quote_value').val(0.00);
            });


        });

        function get_package_protocol_data_testing(id, currency_id, product_type, ID_name, pre_row, name_key, sample_type_id) {
            const _tokken = $('meta[name="_tokken"]').attr('value');
            $.ajax({
                url: "<?php echo base_url('Quotes/get_package_protocol_data_testing') ?>",
                method: "post",
                data: {
                    id: id,
                    currency_id: currency_id,
                    type: product_type,
                    _tokken: _tokken
                },
                success: function(data) {
                    data = $.parseJSON(data);
                    $('.test_container').html("");
                    table = "<table class='table test_table'>";
                    table += "<thead>";
                    table += "<tr>";
                    table += "<td><b>Selected Test</b></td>";
                    table += "<td><b>Test Method</b></td>";
                    table += "<td><b>Test Division</b></td>";
                    table += "</tr>";

                    table += "</thead>";
                    table += "<tbody>";
                    table += "</tbody>";
                    table += "</table>";
                    var delIcon = '<?php echo base_url('assets/images/del.png') ?>';
                    noOpt = "<tr><td colspan='7' disabled>No Record Found</td></tr>";
                    $('.test_container').append(table);


                    pre_row = 0;
                    $.each(data, function(index1, value) {
                        if (value.test_price == 'null' || value.test_price == null) {
                            price = 0;

                        } else {
                            price = value.test_price;
                        }
                        $('.price_packagesProto').val(price);
                        $('.charge_packagesProto').val(price);



                        var row = "<tr>";
                        row += "<td><input type='hidden' name ='" + name_key + "[" + pre_row + "][test_division_id]' value='" + value.test_division_id + "' class='div_id" + index1 + "' data-type='" + product_type + "'><input type='hidden' name ='" + name_key + "[" + pre_row + "][works_sample_type_id]' value='" + sample_type_id + "' class='work_sample_type_id" + pre_row + "' data-type='" + product_type + "'><input type='hidden' name ='" + name_key + "[" + pre_row + "][" + ID_name + "]' value='" + value.package_id + "' class='test_package_id" + pre_row + "' data-type='" + product_type + "'><input type='hidden' name ='" + name_key + "[" + pre_row + "][test_id]' class='form-control form-control-sm test_id" + pre_row + "' value='" + value.test_id + "' data-type='" + product_type + "' >" + value.test_name + "</td>";

                        row += "<td><input type='text' name ='" + name_key + "[" + pre_row + "][test_method]' class='form-control form-control-sm test_method" + pre_row + "' value='" + value.test_method + "' readonly data-type='" + product_type + "'></td>";

                        row += "<td><input type='text' name ='" + name_key + "[" + pre_row + "][work_division_name]' class='form-control form-control-sm work_div_name" + pre_row + "' value='" + value.work_division_name + "' readonly data-type='" + product_type + "'></td>";


                        if (index1 == 0) {
                            row += "<td><input type='number' name ='" + name_key + "[" + pre_row + "][price]'  class='form-control form-control-sm rate price_test" + pre_row + "' value='" + price + "' readonly style='background-color:#B0B0B0;' data-type='" + product_type + "' ></td>";

                            row += "<td><input type='number' name ='" + name_key + "[" + pre_row + "][discount]' class='form-control form-control-sm  discount discount_test" + pre_row + "' value='0'  data-type='" + product_type + "' readonly></td>";

                            row += "<td><input type='number' name ='" + name_key + "[" + pre_row + "][total_cost]' class='form-control form-control-sm applicable_charge applicable_charge_test" + pre_row + "' value='" + price + "'  data-type='" + product_type + "'></td>";

                            row += "<td><button type='button' value='" + value.test_id + "' title='Remove' class='del_test btn btn-default btn-sm' data-value='' data-id='" + pre_row + "'><img src='" + delIcon + "'></button></td>";

                        }
                        row += "</tr>";

                        $('.test_container table tbody').append(row);

                        var rowsapan = $('.test_container table tbody tr').length;
                        $('.del_test').attr("data-value", rowsapan);
                        $('.discount_test0').attr('disabled', true);

                        $(document).on('keyup change', '.discount_packagesProto', function() {
                            var app_charge = 0;
                            var disc = $(this).val();
                            var price = $('.price_packagesProto').val();

                            app_charge = (value.price) - [(value.price) / 100] * disc;

                            $('.charge_packagesProto').attr('value', app_charge.toFixed(2));
                            $('.discount_test0').attr('value', disc);
                            $('.applicable_charge_test0').attr('value', app_charge.toFixed(2));
                        })
                        pre_row++;
                    })
                }
            });
        }

        $('#contact_division').select2({
            allowClear: true,
            ajax: {
                url: "<?php echo base_url('Quotes/get_contact_division'); ?>",
                dataType: 'json',
                data: function(params) {
                    return {
                        key: params.term, // search term
                    };
                },
                processResults: function(response) {

                    return {
                        results: response
                    };
                },
                cache: true
            },
            placeholder: 'Search contact division',
            minimumInputLength: 0,
            templateResult: formatRepo,
            templateSelection: formatRepoSelection
        });

        function formatRepo(repo) {
            if (repo.loading) {
                return repo.text;
            }
            var $container = $(
                "<div class='select2-result-repository clearfix'>" +
                "<div class='select2-result-repository__title'></div>" +
                "</div>"
            );

            $container.find(".select2-result-repository__title").text(repo.name);
            return $container;
        }

        function formatRepoSelection(repo) {
            return repo.full_name || repo.text;
        }

        $('#contact_division').change(function() {
            var division_id = $('#contact_division').val();
            const _tokken = $('meta[name="_tokken"]').attr('value');
            $.ajax({
                type: 'post',
                url: '<?php echo base_url('Quotes/get_contact_details'); ?>',
                data: {
                    _tokken: _tokken,
                    contact_division_id: division_id
                },
                dataType: 'json',
                success: function(data) {
                    CKEDITOR.instances['contact_details'].setData(data.contact_person_details);
                }
            })
        });

    });
</script>