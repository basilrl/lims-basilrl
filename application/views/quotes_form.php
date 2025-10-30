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
</style>
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
                        <li class="breadcrumb-item"><a href="">HOME</a></li>
                        <li class="breadcrumb-item"><a href="">QUOTES</a></li>
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

                                <input type="hidden" value="" name="sample_type_id_quote" class="sample_type_id_quote">
                                <input type="hidden" value="" name="sample_type_category_quote" class="sample_type_category_quote">
                                <input type="hidden" name="quote_id" value="<?php echo $quote_id?>" class="quote_id">

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
                                    <?php echo form_error('customer_type'); ?>
                                </div>
                                <div class="col-sm-6">
                                    <label for="">Customer:</label>
                                    <div class="row ">

                                        <div class="col-sm-10">

                                            <select name="quotes_customer_id" id="" class="form-control form-control-sm customer">

                                            </select>
                                            <?php echo form_error('quotes_customer_id'); ?>
                                        </div>

                                        <div class="col-sm-2">
                                            <label for="">Add</label>
                                            <button  type="button "class="bg-primary add_more_btn_cust" data-bs-toggle="modal" data-bs-target=".add_more_cust_popup">
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
                                            <?php echo form_error('quotes_contact_id'); ?>
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
                                    <input type="hidden" name="quotes_branch_id" value="<?php echo $branch_id ?>" id="">
                                    <input type="text" name="quotes_branch_name" value="<?php echo $branch_name ?>" id="" readonly class="form-control form-control-sm" style="background-color:#B0B0B0">
                                    <?php echo form_error('quotes_branch_name'); ?>
                                </div>
                                <div class="col-sm-6">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label for="">Currency:</label>
                                            <select name="quotes_currency_id" id="" class="form-control form-control-sm currency" value="">

                                            </select>
                                            <?php echo form_error('quotes_currency_id'); ?>
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
                           
                                <div class="col-sm-6">
                                    
                                    <input type="date" name="discussion_date" id="" value="<?php echo $discussion_date ?>" class="form-control form-control-sm disc_date">
                                </div>

                                <div class="col-sm-6">
                                    <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target=".quote_window" > Add Quotes Details</button>
                                </div>

                            </div>

                            <hr>

                            <div class="row p-2">
                                <div class="table-responsive quotes_details_table">
                                    <table class="table">
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
                                        <tbody>
                                       
                                            <?php $i=0; if($test_data && count($test_data)>0){?>
                                                <?php foreach($test_data as $key=>$item){?>
                                                    <tr>
                                                        <td><input type='hidden' name ='test_data[<?php echo $i?>][test_division_id]' value='<?php echo $item->test_division_id?>'><input type='hidden' name ='test_data[<?php echo $i?>][test_id]' class='form-control form-control-sm' value='<?php echo $item->id?>'><?php echo $item->name?></td>

                                                        <td><input type='text' name ='test_data[<?php echo $i?>][test_method]' class='form-control form-control-sm' value='<?php echo $item->test_method?>' readonly></td>

                                                         <td><input type='text' name ='test_data[<?php echo $i?>][work_division_name]' class='form-control form-control-sm' value='<?php echo $item->work_division_name?>' readonly></td>

                                                         <td><input type='number' name ='test_data[<?php echo $i?>][price]'  class='form-control form-control-sm rate' value='<?php echo $item->price?>' readonly style='background-color:#B0B0B0'></td>

                                                        <td><input type='number' name ='test_data[<?php echo $i?>][discount]' class='form-control form-control-sm discount' value='<?php echo $item->discount?>' readonly></td>

                                                        <td><input type='number' name ='test_data[<?php echo $i?>][applicable_charge]' class='form-control form-control-sm applicable_charge' value='<?php echo $item->applicable_charge ?>' readonly></td>

                                                         <td><button value='<?php echo $item->id?>' title='Remove' class='del_test'><img src='<?php echo base_url('assets/images/del.png')?>'></button></td>
                         
                                                        </tr>
                                                <?php $i++; }?>
                                           <?php }?>
                                        </tbody>
                                    </table>
                                    
                                </div>
                            </div>


                            <div class="row p-2">
                                <div class="col-sm-6">
                                    <label for="">Approver's Designation:</label>
                                    <select name="quote_signing_authority_designation_id" class="form-control form-control-sm approver_dsg" value="" id="">
                                    </select>
                                    <?php echo form_error('quote_signing_authority_designation_id'); ?>
                                </div>

                                <div class="col-sm-6">
                                    <label for="">Quote Value:</label>
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
                                    <select name="quotes_signing_authority_id" class="form-control form-control-sm approver" value="" id="">
                                    </select>
                                    <?php echo form_error('quotes_signing_authority_id'); ?>
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
                                    <textarea class="ckeditor" name="quote_subject"><?php echo $quote_subject?></textarea>
                                </div>

                                <div class="col-sm-6">
                                    <label for="">Salutation and Greetings</label>
                                    <textarea class="ckeditor" name="salutation"><?php echo $salutation?></textarea>
                                </div>

                            </div>

                            <div class="row p-2">

                                <div class="col-sm-6">
                                    <label for="">Terms and Conditions</label>
                                    <textarea class="ckeditor" name="terms_conditions"><?php echo $terms_conditions?></textarea>
                                </div>

                                <div class="col-sm-6">
                                    <label for="">Payment Terms</label>
                                    <textarea class="ckeditor" name="payment_terms"><?php echo $payment_terms?></textarea>
                                </div>

                            </div>

                            <div class="row p-2">

                                <div class="col-sm-6">
                                    <label for="">Sample Retention</label>
                                    <textarea class="ckeditor" name="sample_retention"><?php echo $sample_retention?></textarea>
                                </div>

                                <div class="col-sm-6">
                                    <label for="">Additional Notes</label>
                                    <textarea class="ckeditor" name="additional_notes"><?php echo $additional_notes?></textarea>
                                </div>

                            </div>

                            <div class="row p-2 text-right">
                                <div class="col-sm-12">

                                    <a href="<?php echo base_url('quotes') ?>" class="btn btn-primary" type="submit">Back</a>
                                    <button class="btn btn-primary" type="submit">Submit</button>
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
            <div class="modal-body">
              

                    <div class="row p-2">
                        <div class="col-sm-6">
                            <select name="" id="" class="form-control form-control-sm quotes_details_type" >
                                <option value="" selected>Select Quotes Type</option>
                                <option value="PR">Products</option>
                                <!-- <option value="DQ">Division Quote</option>
                                <option value="SP">Sample Pickup</option> -->
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
                <button type="button" class="btn btn-primary quotes_submit" >Save</button>
            </div>
           
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
                            <select name="quotes_country_id" id="" class="form-control form-control-sm country">

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

        update_quote_value();

        var PR_html = '<div class="col-sm-4">';
        PR_html+='<label for="">Product Category:</label>';
        PR_html+='<select name="category" id="" class="form-control form-control-sm product_cat" value=""></select>';
        PR_html+='</div>';

        PR_html+='<div class="col-sm-4">';
        PR_html+='<label for="">Product:</label>';
        PR_html+='<select name="product" id="" class="form-control form-control-sm product" value=""></select>';
        PR_html+='</div>';

        PR_html+='<div class="col-sm-4">';
        PR_html+='<label for="">Product Type:</label>';
        PR_html+='<select name="product_type" id="" class="form-control form-control-sm product_type" value="">';
        PR_html+='</select>';
        PR_html+='</div>';

       var q_type =  $('.quotes_details_type');
       var q_detail =  $('.quotes_details');
       q_type.on('change',function(){
            var d_value = $(this).val();
            if(d_value=="PR"){
                q_detail.html("");
                q_detail.html(PR_html);
                get_product_cat();  
            }
       })

      
       $(document).on('click','.quotes_submit',function(){
            var tableHtml = $('.test_container').children("table").children("tbody").html();
             var manage_details =  $('.manage_details').html();
             
            if(manage_details==""){
                $.notify('Cannot save empty details','error');
            }
            else{
                $('.quotes_details_table table tbody').append(tableHtml);
                $('.quote_window').modal("hide");
                $.notify('Product Details saved successfully','success');
                $('.manage_details').html("");
            }
           
          

       })

       $(document).on('change', '.product_type', function() {
            var product_type = $(this).val();
            $('.sample_type').val(product_type);
            var sample_type_id = $('.product').val();
            var currency_id = $('.currency').val();

            if (product_type == "0") {

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
                manage_div += "<button type='button' class='btn btn-sm bg-primary add_test_button'>ADD</button>";
                manage_div += "</div>";

                manage_div += "</div>";

                manage_div += "<div class='row'>";

                manage_div += "<div class='col-sm-12'>";
                manage_div += "<div class='test_container table-responsive'>";
                manage_div += "</div></div>";

                manage_div += "</div>";
                $('.manage_details').html(manage_div);
                $('.manageTest').select2();
                get_tests(sample_type_id, currency_id);

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
        })

        $('.customer_type').on('change', function() {
            var type = $(this).val();
            var customer_id = "";
            get_customer_by_type(type, customer_id);
        })

        var customer_type = $('.customer_type').val();
        var customer_id = "<?php echo $customer_id ?>";
        var contact_id = "<?php echo $contact_id ?>";

        get_customer_by_type(customer_type, customer_id);
        get_contact_by_customer_id(customer_id, contact_id);
        var d_id = "<?php echo $desgination_id ?>";
        var ap_id= "<?php echo $approver_id?>";
        get_designation(d_id);
        get_approver(d_id,ap_id);
        // product cat
        get_product_cat();



        $(document).on('change', '.product_cat', function() {
            var cat_id = $(this).val();
            get_product_by_category_id(cat_id);
            $('.sample_type_category_quote').val(cat_id);

        })
        $(document).on('change', '.product', function() {
            var product_id = $(this).val();
            $('.sample_type_id_quote').val(product_id);
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
            $("#add_contact_form").trigger('reset');
            var customer_type = $('.customer_type').val();
            var customer_name = $(".customer option:selected").text();
            $('.contact_customer_type').val(customer_type);
            $('.contact_customer_name').val(customer_name);
            get_country();
        })

        $('.add_more_btn_cust').on('click', function() {
            $("#add_customer_form").trigger('reset');
            var customer_type = $('.customer_type').val();
            $('.cust_customer_type').val(customer_type);
            get_country();
        })

        $('#add_contact_form').on('submit', function() {
            var customer_id = $('.contacts_customer_id').val();
            var contact_id = "";
            submitContacts();
            get_contact_by_customer_id(customer_id, contact_id);
        })

        $('#add_customer_form').on('submit', function() {
            var type = $('.cust_customer_type').val();
            var customer_id = "";
            submitCustomers();
            get_customer_by_type(type, customer_id);
        })


        // manage details


      




        $(document).on('click', '.add_test_button', function() {
            var test_ids = $('.manageTest').val();
            var currency_id = $('.currency').val();
            $('.test_submit').css("display", "block");
            get_test_container_window(test_ids, currency_id);

        })


        $(document).on('click', '.del_test', function() {
            $(this).parents("tr").remove();
        })





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
                    noOpt = "<option value=''>No Record Found</option>";
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
            })
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
                    noOpt = "<option value=''>No Record Found</option>";
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
            })
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
                    noOpt = "<option value=''>No Record Found</option>";
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
            })
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
                    noOpt = "<option value=''>No Record Found</option>";
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
            })
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
                    noOpt = "<option value=''>No Record Found</option>";
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
            })
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
                    noOpt = "<option value=''>No Record Found</option>";
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
            })
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
                }
            })
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
            })
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
                    noOpt = "<option value=''>No Record Found</option>";
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
            })
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
                    noOpt = "<option value=''>No Record Found</option>";
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
            })
        }

        // get product type

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
                    noOpt = "<option value=''>No Record Found</option>";
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
            })
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
                    option = "<option value=''>Select Tests</option>";
                    noOpt = "<option value=''>No Record Found</option>";
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
            })
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
                    noOpt = "<tr><td colspan='7'>No Record Found</td></tr>";
                    $('.test_container').append($(table));
                    if (data) {
                        $.each(data, function(i, v) {

                            var price = v.price;
                            var discount = 0;
                            var applicable_charge = price;

                            row = "<tr>";
                            row += "<td><input type='hidden' name ='test_data[" + i + "][test_division_id]' value='" + v.test_division_id + "'><input type='hidden' name ='test_data[" + i + "][test_id]' class='form-control form-control-sm' value='" + v.id + "'>" + v.name + "</td>";
                            row += "<td><input type='text' name ='test_data[" + i + "][test_method]' class='form-control form-control-sm' value='" + v.test_method + "' readonly></td>";
                            row += "<td><input type='text' name ='test_data[" + i + "][work_division_name]' class='form-control form-control-sm' value='" + v.work_division_name + "' readonly></td>";
                            row += "<td><input type='number' name ='test_data[" + i + "][price]'  class='form-control form-control-sm rate' value='" + price + "' readonly style='background-color:#B0B0B0'></td>";
                            row += "<td><input type='number' name ='test_data[" + i + "][discount]' class='form-control form-control-sm discount' value='" + discount + "' readonly></td>";
                            row += "<td><input type='number' name ='test_data[" + i + "][applicable_charge]' class='form-control form-control-sm applicable_charge' value='" + applicable_charge + "' readonly></td>";
                            row += "<td><button value='" + v.id + "' title='Remove' class='del_test'><img src='" + delIcon + "'></button></td>";
                            row += "</tr>";
                            $('.test_table tbody').append($(row));

                        })

                    } else {
                        $('.test_table tbody').append($(noOpt));
                    }

                    update_quote_value();
                }
            })


        }


        function update_quote_value(){

            var quote_v = $('.applicable_charge');
                    var sum_quote = 0;
                    $.each(quote_v, function(index, value) {
                        sum_quote += parseFloat(value.value);
                    })
            $('.quote_value').val(sum_quote);
        }


        // calculation

        
        $(document).on('change', '.discount', function() {
           
            var discount = $(this).val();
            var rate = $(this).parents("td").prev("td").children(".rate").val();
            var new_charge = rate - [(rate * discount) / 100];
            $(this).parents("td").next("td").children(".applicable_charge").val(new_charge);
            update_quote_value();
        })

        $(document).on('change', '.applicable_charge', function() {
            var charge = $(this).val();
            var rate = $(this).parents("td").prev("td").prev("td").children(".rate").val();
            if (charge > rate) {
                $(this).parents("td").prev("td").prev("td").children(".rate").val(charge);
                $(this).parents("td").prev("td").children(".discount").val(charge);

            } else {
                var new_discount = [(rate - charge) * 100] / rate;
                $(this).parents("td").prev("td").children(".discount").val(new_discount);
                
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
                    noOpt = "<option value=''>No Record Found</option>";
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
            })
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
                    noOpt = "<option value=''>No Record Found</option>";
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
            })
        }



        // insert_quotes

        $('#quotes').submit(function(e) {
            e.preventDefault();
                var quote_id = $('.quote_id').val();
                if(quote_id==""){
                   url_ =  "<?php echo base_url('Quotes/add_quote') ?>";
                }
                else{
                    url_ =  "<?php echo base_url('Quotes/update_quote') ?>";
                }
            $.ajax({
                url: url_ ,
                method: "post",
                data: new FormData(this),
                contentType: false,
                processData: false,
                success: function(data) {
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
            })
        })



        CKEDITOR.replace('quote_subject');
        CKEDITOR.replace('salutation');
        CKEDITOR.replace('terms_conditions');
        CKEDITOR.replace('additional_notes');
        CKEDITOR.replace('payment_terms');
        CKEDITOR.replace('quote_subject');


    })
</script>