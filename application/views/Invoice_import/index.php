<style>
    #application_name-error,
    #application_desc-error {
        color: red;
    }
</style>
<script src="<?php echo base_url(); ?>assets/js/xlsx.full.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jszip.js"></script>
<script src="<?php echo base_url() ?>ckeditor/ckeditor.js"></script>
<script src="<?php echo base_url(); ?>assets/js/invoice_import.js"></script>
<main class="main">
    <div class="container text-center"><br /><br />
        <h2><i class="fa fa-bars"></i> ACCOPS LIST</h2>
    </div>


    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-3">
                <?php if(exist_val('Invoice_import/add_client_details',$this->session->userdata('permission'))){ ?>
                    <button type="button" class="btn btn-sm btn-primary add_application" data-bs-toggle="modal" data-bs-target="#add_application"><span> <i class="fa fa-plus"> </i></span> ADD DETAILS</button>
                <?php } ?>
                <?php if(exist_val('Invoice_import/add_invoice_details',$this->session->userdata('permission'))){ ?>
                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#email_flag"><span> <i class="fa fa-plus"> </i></span> ADD INVOICE DETAILS</button>
                <?php } ?>
            </div>
            <div class="col-sm-9">
                <div class="row">
                    <div class="col-sm-2">
                        <div class="form-group row">
                            <label for="inputPassword" class="col-sm-6 col-form-label small"><small>PER PAGE</small></label>
                            <div class="col-sm-6 small">
                                <select id="per_page" class="form-control form-control-sm small">
                                    <option value="10">10</option>
                                    <option value="20">20</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">

                    </div>
                    <div class="col-sm-4">
                        <input type="text" class="search form-control form-control-sm" placeholder="ENTER CUSTOMER CODE & CUSTOMER NAME" name="" id="">
                    </div>
                    <div class="col-sm-2">
                        <button class="btn btn-sm btn-primary search_listing">SEARCH</button>
                        <button class="btn btn-sm btn-danger clear_listing">CLEAR</button>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div class=" mt-3 table-responsive small">
        <table class="table table-sm">
            <thead>
                <tr>
                    <th scope="col">SL NO</th>
                    <th>CUSOMTER CODE</th>
                    <th>CUSTOMER NAME</th>
                    <th>BALANCE</th>
                    <th>LESS 60 DAYS</th>
                    <th>LESS 90 DAYS</th>
                    <th>LESS 120 DAYS</th>
                    <th>LESS 180 DAYS</th>
                    <th>GREATER 180 DAYS</th>
                    <th>CREATED BY</th>
                    <th>STATUS</th>
                    <th>DATE</th>
                    <th>ACTION</th>
                </tr>
            </thead>
            <tbody id="application_list"></tbody>
        </table>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-sm-4"></div>
            <div class="col-sm-4" id="application_pagination"></div>
            <div class="col-sm-4"></div>
        </div>
    </div>
</main>

<div class="modal fade" id="email_flag" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">ADD INVOICE DETAILS</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="row">
                <div class="col-sm-12 text-center role_errors"></div>
            </div>
            <form method="post" name="submit_application" action="javscript:void(0);" class="add_invoice_submit">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-6"><input type="file" name="client_detail" id="invoice_details"></div>
                        <div class="col-sm-6"><a href="<?php echo base_url('public/file/invoice_details.xlsx'); ?>" download="" class="btn btn-sm btn-primary">DEMO FILE</a></div>
                    </div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">CLOSE</button><button type="submit" id="form-submit" class="btn btn-primary">ADD</button></div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="add_application" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">ADD CLIENT DETAILS</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="row">
                <div class="col-sm-12 text-center role_errors"></div>
            </div>
            <form method="post" name="submit_application" action="javscript:void(0);" class="add_submit">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-6"><input type="file" name="client_detail" id="excelfile"></div>
                        <div class="col-sm-6"><a href="<?php echo base_url('public/file/accob_client.xlsx'); ?>" class="btn btn-sm btn-primary">DEMO FILE</a></div>
                    </div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">CLOSE</button><button type="submit" id="form-submit" class="btn btn-primary">ADD</button></div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="invoice_details_models" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">View Invoice Details</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12" id="set_invoice_Details">

                    </div>
                </div>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">CLOSE</button></div>
        </div>
    </div>
</div>

<div class="modal fade" id="send_mail" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">View Invoice Details</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <form action="javascript:void(0);" method="post" class="send_mail_perticular">
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                <input type="hidden" name="id" value="">
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row" id="emails_set">

                        </div>
                        <div class="row mt-5">
                            <div class="col-sm-12">
                                <input type="text" class="form-control form-control-sm" placeholder="ENTER THE TO EMAIL" name="email" value="" id="">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-sm-12">
                                <input type="text" class="form-control form-control-sm" placeholder="ENTER THE CC EMAIL" name="email_cc" value="" id="">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-sm-12">
                                <input type="text" name="subject" placeholder="ENTER THE SUBJECT" class="form-control form-control-sm">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-sm-12">
                                <textarea class="form-control form-control-sm invoice_details_Text_mail" name="text" id="" cols="30" rows="10"></textarea>
                                <script>
                                    CKEDITOR.replace('text');
                                </script>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">CLOSE</button><button class="btn tbn-sm btn-success" type="submit">SUBMIT</button></div>
            </form>
        </div>
    </div>
</div>