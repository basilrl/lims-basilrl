<style>
    #application_name-error,
    #application_desc-error {
        color: red;
    }
</style>
<script src="<?php echo base_url(); ?>public/js/Stock_Utilization_Report.js"></script>
<main class="main" style="min-height: 80vh;">
    <div class="container text-center"><br /><br />
        <h2><i class="fa fa-bars"></i> Stock Utilization Report </h2>
    </div>


    <div class="container-fluid">
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
                    <div class="col-sm-6">
                        <div class="row">
                            <div class="col-sm-3"><label for="">FROM:</label></div>
                            <div class="col-sm-9"><input class="form-control form-control-sm" type="date" name="" id="start_date"></div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="row">
                            <div class="col-sm-3"><label for="">TO:</label></div>
                            <div class="col-sm-9"><input class="form-control form-control-sm" type="date" name="" id="end_date"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-2">
                 <div class="row">
                     <div class="col-sm-6"><a class="search_listing" href="javascript:void(0);">GENERATE</a></div>
                     <div class="col-sm-6"><a href="javascript:void(0);">CLEAR</a></div>
                 </div>                   
            </div>
        </div>
    </div>



    <div class=" mt-3 table-responsive small">
        <table class="table table-sm">
            <thead>
                <tr>
                    <th scope="col">SL NO</th>
                    <th>Opening Stock</th>
                    <th>Stock Consumption Date</th>
                    <th>Consumption Quantity</th>
                    <th>Unit</th>
                    <th>Closing Stock</th>
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
