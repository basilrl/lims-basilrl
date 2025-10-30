<style>
    #application_name-error,
    #application_desc-error {
        color: red;
    }
</style>
<script src="<?php echo base_url(); ?>assets/js/Item.js"></script>
<main class="main">
    <div class="container text-center"><br /><br />
        <h2><i class="fa fa-bars"></i> Item </h2>
    </div>


    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-3">
                <?php if (exist_val('Item/add', $this->session->userdata('permission'))) { ?>
                <button type="button" class="btn btn-sm btn-primary add_application" data-bs-toggle="modal" data-bs-target="#add_Store"><span> <i class="fa fa-plus"> </i></span></button>
                <?php }  ?>
            </div>
            <div class="col-sm-9">
                <div class="row">
                    <div class="col-sm-6">
                        <input type="text" class="search form-control form-control-sm" placeholder="ENTER BY NAME" name="" id="">
                    </div>
                    <div class="col-sm-4">
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
                    <th>Item Name</th>
                    <th>Category Name</th>
                    <th>Unit Name</th>
                    <th>Minimum</th>
                    <th>Action</th>
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

<div class="modal fade" id="add_Store" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Category</h5><button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <form method="post" name="submit_application" action="javscript:void(0);" class="add_submit">
                <input type="hidden" name="item_id" value="">
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12 text-center role_errors"></div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <label for="">Item Name</label>
                            <input type="text" name="item_name" class="form-control form-control-sm">
                        </div>
                        <div class="col-sm-6">
                            <label for="">Category</label>
                            <select name="category_id" class="form-control form-control-sm category" id=""></select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="row">
                                <div class="col-sm-6"><label for="">Min. Quantity Required</label>
                                    <input type="number" name="min_quantity_required" min="0" class="form-control form-control-sm">
                                </div>
                                <div class="col-sm-6">
                                    <br>
                                    <div class="form-check">
                                        <input class="form-check-input" name="critical_item" type="checkbox" value="1" id="defaultCheck1">
                                        <label class="form-check-label" for="defaultCheck1"> Critical Item </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label for="">Unit</label>
                            <select name="unit" class="form-control form-control-sm unit" id=""></select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">CLOSE</button><button type="submit" id="form-submit" class="btn btn-primary">ADD</button></div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="role_permission" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">ADD STOCK DETAILS</h5><button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <form method="post" class="add_stock_form_submit" action="javscript:void(0);">
                <input type="hidden" name="item_id" value="">
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="row mb-4">
                                <div class="col-sm-6">
                                    <div class="row">
                                        <div class="col-sm-2"><label for="">PO NUMBER:</label></div>
                                        <div class="col-sm-10"><input type="text" name="po_num" id="" class="form-control form-control-sm"></div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                <div class="row">
                                        <div class="col-sm-1"><label for="">STORE:</label></div>
                                        <div class="col-sm-11">
                                        <select class="form-control form-control-sm" name="item_store_id" id="">
                                            <option value="">SELECT A STORE</option>
                                            <?php if($store){ ?>
                                                <?php foreach($store as $store_name){ ?>
                                                    <option value="<?php echo $store_name->store_id ?>"><?php echo $store_name->store_name ?></option>
                                                <?php } ?>
                                            <?php } ?>
                                        </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-sm-6">
                                    <div class="row">
                                        <div class="col-sm-2"><label for="">QUANTITY:</label></div>
                                        <div class="col-sm-10"><input type="text" name="item_quantity" id="" class="form-control form-control-sm"></div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                <div class="row">
                                        <div class="col-sm-1"><label for="">UNIT:</label></div>
                                        <div class="col-sm-11">
                                        <select class="form-control form-control-sm" name="stock_item_unit" id="">
                                            <option value="">SELECT A UNIT</option>
                                             <?php if($unit){ ?>
                                                <?php foreach($unit as $unit_name){ ?>
                                                    <option value="<?php echo $unit_name->unit_id ?>"><?php echo $unit_name->unit ?></option>
                                                <?php } ?>
                                            <?php } ?>
                                        </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-4" >
                                <div class="col-sm-6">
                                    <div class="row">
                                        <div class="col-sm-2"><label for="">PO DATE:</label></div>
                                        <div class="col-sm-10"><input type="date" name="po_date" value="<?php echo date('Y-m-d'); ?>" id="" class="form-control form-control-sm"></div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                <div class="row">
                                        <div class="col-sm-1"><label for="">PURCHASE VALUE:</label></div>
                                        <div class="col-sm-11"><input type="text" name="purchase_value" id="" class="form-control form-control-sm"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-sm-6">
                                    <div class="row">
                                        <div class="col-sm-2"><label for="">INVOICE NUMBER:</label></div>
                                        <div class="col-sm-10"><input type="text" name="invoice_num" id="" class="form-control form-control-sm"></div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                <div class="row">
                                        <div class="col-sm-1"><label for="">INVOICE DATE:</label></div>
                                        <div class="col-sm-11"><input type="date" name="invoice_date" id="" value="<?php echo date('Y-m-d'); ?>" class="form-control form-control-sm"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="row">
                                        <div class="col-sm-2"><label for="">INTENT NUMBER:</label></div>
                                        <div class="col-sm-10"><input type="text" name="intent_num" id="" class="form-control form-control-sm"></div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                <div class="row">
                                        <div class="col-sm-1"><label for="">NOTE:</label></div>
                                        <div class="col-sm-11"><textarea class="form-control form-control-sm" name="stock_notes" id=""></textarea></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">CLOSE</button><button type="submit" class="btn btn-primary">UPDATE</button></div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="user_log" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">LOG</h5><button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12" id="log_html"></div>
                    </div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">CLOSE</button></div>
        </div>
    </div>
</div>
