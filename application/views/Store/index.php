<style>
    #application_name-error,
    #application_desc-error {
        color: red;
    }
</style>
<script src="<?php echo base_url(); ?>assets/js/Store.js"></script>
<main class="main">
    <div class="container text-center"><br /><br />
        <h2><i class="fa fa-bars"></i> Store Master</h2>
    </div>


    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-3">
            <?php if (exist_val('Store_management/add', $this->session->userdata('permission'))) { ?>
                <button type="button" class="btn btn-sm btn-primary add_application" data-bs-toggle="modal" data-bs-target="#add_Store"><span> <i class="fa fa-plus"> </i></span></button>
            <?php } ?>
            </div>
            <div class="col-sm-9">
                <div class="row">
                    <div class="col-sm-6">
                        <input type="text" class="search form-control form-control-sm" placeholder="ENTER Store NAME" name="" id="">
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
                    <th>Store Name</th>
                    <th>Branch Name</th>
                    <th>Store Keeper</th>
                    <th>Created On</th>
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
                <h5 class="modal-title" id="exampleModalLongTitle">Store</h5><button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <form method="post" name="submit_application" action="javscript:void(0);" class="add_submit">
            <input type="hidden" name="store_id" value="">
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 text-center role_errors"></div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <label for="">Store Name</label>
                        <input type="text" name="store_name" class="form-control form-control-sm">
                    </div>
                    <div class="col-sm-6">
                        <label for="">Branch</label>
                      <select class="form-control form-control-sm" name="store_branch_id" id=""></select>
                    </div>
                </div>
                    <div class="row p-2">
                        <div class="col-sm-6">
                        <label for="">Store Keeper</label>
                        <select name="store_store_keeper_id" id="" class="form-control form-control-sm">
                            <option value="">SELECT STORE KEEPER</option>
                        </select>
                        </div>
                        <div class="col-sm-6">
                            <div class="row mt-4">
                                <div class="col-sm-6">
                                <div class="form-check">
                                <input class="form-check-input" name="low_stock_notif_req" type="checkbox" value="1" id="defaultCheck1">
                                <label class="form-check-label" for="defaultCheck1">
                                Low Stock Notification Required
                                </label>
                                </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-check">
                                    <input class="form-check-input" name="main_store" type="checkbox" value="1" id="defaultCheck1">
                                    <label class="form-check-label" for="defaultCheck1">
                                    Main Store (Only main store will be displayed in add stock.)
                                    </label>
                                    </div>
                                </div>
                            </div>
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
                <h5 class="modal-title" id="exampleModalLongTitle">PERMISSION</h5><button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <form method="post" class="role_permission" action="javscript:void(0);">
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                <input type="hidden" name="role_id" class="role_id" value="">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="row" id="permission_Set_html">
                                
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