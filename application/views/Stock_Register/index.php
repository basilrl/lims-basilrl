<style>
    #application_name-error,
    #application_desc-error {
        color: red;
    }
</style>
<script src="<?php echo base_url(); ?>public/js/Stock_Register.js"></script>
<main class="main" style="min-height: 80vh;">
    <div class="container text-center"><br /><br />
        <h2><i class="fa fa-bars"></i> Stock Register </h2>
    </div>


    <div class="container-fluid">
        <form action="javascript:void(0);" id="serach_form" method="post">
            <div class="row">
                <div class="col-sm-3">
                    <div class="row">
                        <div class="col-sm-4"><label for="">SELECT STORE:</label></div>
                        <div class="col-sm-8">
                            <select class="form-control form-control-sm" name="" id="store_id">
                                <option value="">SELECT STORE</option>
                                <?php if ($store) { ?>
                                    <?php foreach ($store as $val) { ?>
                                        <option value="<?php echo $val->store_id; ?>"><?php echo $val->store_name; ?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="row">
                        <div class="col-sm-4"><label for="">SELECT ITEM:</label></div>
                        <div class="col-sm-8">
                            <select class="form-control form-control-sm" name="" id="item_id">
                                <option value="">SELECT ITEM</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-sm-3"><label for="">PER PAGE:</label></div>
                                <div class="col-sm-9">
                                    <select class="form-control form-control-sm" name="" id="per_page">
                                        <option value="10">10</option>
                                        <option value="20">20</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="row">
                        <div class="col-sm-6"><a class="btn btn-sm btn-primary search_listing" href="javascript:void(0);">SEARCH</a></div>
                        <div class="col-sm-6"><a class="btn btn-sm btn-danger clear_listing" href="javascript:void(0);">CLEAR</a></div>
                    </div>
                </div>
            </div>
        </form>
    </div>



    <div class=" mt-3 table-responsive small">
        <table class="table table-sm">
            <thead>
                <tr>
                    <th scope="col">SL No.</th>
                    <th>Item Name</th>
                    <th>Store Name</th>
                    <th>Quantity Available</th>
                    <th>Minimum Quantity Required</th>
                    <th>Unit</th>
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

<div class="modal fade" id="stock_cons" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Stock Consumption</h5><button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <form method="post" class="addStockConsumption" action="javscript:void(0);">
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                <input type="hidden" name="item_id" value="">
                <input type="hidden" name="store_id" value="">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-sm-6"> <label for=""> Quantity:</label>
                                    <input type="number" name="stock_consumption_quantity" class="form-control form-control-sm" value="">
                                </div>
                                <div class="col-sm-6">
                                    <label for="">Unit:</label>
                                    <select class="form-control form-control-sm" name="stock_consumption_unit" id="">
                                        <option value="">SELECT A UNIT</option>
                                        <?php if ($unit) { ?>
                                            <?php foreach ($unit as $key => $value) { ?>
                                                <option value="<?php echo $value->unit_id ?>"><?php echo $value->unit; ?></option>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <label for="">Note:</label>
                                    <textarea class="form-control form-control-sm" name="stock_consumption_notes" id="" cols="30" rows="10"></textarea>
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
<div class="modal fade" id="trnasfer_stock_cons" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Transfer Stock</h5><button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <form method="post" class="trnasfer_stock_cons" action="javascript:void(0);">
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                <input type="hidden" name="item_id" value="">
                <input type="hidden" name="store_id" value="">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="row">
                            <div class="col-sm-4">
                            <label for=""> Store:</label>
                            <select class="form-control form-control-sm" name="item_transfer_store_id" >
                                <option value="">SELECT STORE</option>
                                <?php if ($store) { ?>
                                    <?php foreach ($store as $val) { ?>
                                        <option value="<?php echo $val->store_id; ?>"><?php echo $val->store_name; ?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                            </div>
                                <div class="col-sm-4"> <label for=""> Quantity:</label>
                                    <input type="number" name="item_transfer_quantity" class="form-control form-control-sm" value="">
                                </div>
                                <div class="col-sm-4">
                                    <label for="">Unit:</label>
                                    <select class="form-control form-control-sm" name="transfer_item_unit" id="">
                                        <option value="">SELECT A UNIT</option>
                                        <?php if ($unit) { ?>
                                            <?php foreach ($unit as $key => $value) { ?>
                                                <option value="<?php echo $value->unit_id ?>"><?php echo $value->unit; ?></option>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <label for="">Transfer Note:</label>
                                    <textarea class="form-control form-control-sm" name="stock_transfer_notes" id="" cols="30" rows="10"></textarea>
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