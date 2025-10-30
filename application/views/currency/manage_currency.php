<div class="content-wrapper">
    <section class="content-header">
        <!-- container fluid start -->
        <div class="container-fluid">
        <div class="row">
          <div class="col-sm-12 text-center">
          <div class="float-left mt-3">
          <?php if (exist_val('Currency/add_currency', $this->session->userdata('permission'))) { ?>
            <button type="button" class="btn btn-sm btn-primary add" data-bs-toggle="modal" data-bs-target=".add_crncy" title="ADD CURRENCY"> <i class="fa fa-plus"></i> Add</button>
                                          <?php } ?>
                                      </div>
            <h1 class="text-bold mt-3 mb-3">CURRENCY</h1>
          </div>
        </div>
      </div>
      <div class="container-fluid jumbotron p-3">
      <div class="row">
                                <div class="col-sm-12">
                                    <div class="row">
                                        <?php if ($code_currency) {
                                            $currency_code = $code_currency;
                                        } else {
                                            $currency_code = "";
                                        } ?>
                                        <div class="col-sm-3">
                                            <select name="currency_code" id="currency_code" class="form-control form-control-sm">
                                                <option value="">Select Currency Code</option>
                                                <?php foreach ($cn_codes as $dns) { ?>
                                                    <option value="<?php echo $dns->currency_code; ?>" <?php echo ($currency_code == $dns->currency_code) ? "selected" : ""; ?>> <?php echo $dns->currency_code; ?> </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <?php if ($name_currency) {
                                            $currency_name = $name_currency;
                                        } else {
                                            $currency_name = "";
                                        } ?>
                                        <div class="col-sm-3">
                                            <select name="currency_name" id="currency_name" class="form-control form-control-sm">
                                                <option value="">Select Currency</option>
                                                <?php foreach ($cn_name as $cnn) { ?>
                                                    <option value="<?php echo $cnn->currency_name; ?>" <?php echo ($currency_name == $cnn->currency_name) ? "selected" : ""; ?>> <?php echo $cnn->currency_name; ?> </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <?php if ($basic_unit) {
                                            $currency_basic_unit = $basic_unit;
                                        } else {
                                            $currency_basic_unit = "";
                                        } ?>
                                        <div class="col-sm-3">
                                            <select name="currency_basic_unit" id="currency_basic_unit" class="form-control form-control-sm">
                                                <option value="">Select Currency Basic Unit</option>
                                                <?php foreach ($cn_basics as $cnb) { ?>
                                                    <option value="<?php echo $cnb->currency_basic_unit; ?>" <?php echo ($currency_name == $cnb->currency_basic_unit) ? "selected" : ""; ?>> <?php echo $cnb->currency_basic_unit; ?> </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <?php if ($fractional_unit) {
                                            $currency_fractional_unit = $fractional_unit;
                                        } else {
                                            $currency_fractional_unit = "";
                                        } ?>
                                        <div class="col-sm-3">
                                            <select name="currency_fractional_unit" id="currency_fractional_unit" class="form-control form-control-sm">
                                                <option value="">Select Currency Fractional Unit</option>
                                                <?php foreach ($cn_fracts as $cnf) { ?>
                                                    <option value="<?php echo $cnf->currency_fractional_unit; ?>" <?php echo ($currency_fractional_unit == $cnf->currency_fractional_unit) ? "selected" : ""; ?>> <?php echo $cnf->currency_fractional_unit; ?> </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 mt-2">
                                    <div class="row">
                                        <?php if ($created_pesron) {
                                            $uidnr_admin = $created_pesron;
                                        } else {
                                            $uidnr_admin = "";
                                        } ?>
                                        <div class="col-sm-3">
                                            <select name="created_by" id="created_by" class="form-control form-control-sm">
                                                <option value="">Select Created By</option>
                                                <?php foreach ($created_by_name as $cr_name) { ?>
                                                    <option value="<?php echo $cr_name->uidnr_admin; ?>" <?php echo ($uidnr_admin == $cr_name->uidnr_admin) ? "selected" : ""; ?>> <?php echo $cr_name->created_by; ?> </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="col-sm-3">
                                            <select name="status" id="status" class="form-control form-control-sm">
                                                <option value="">Select Status</option>
                                                <option value="1" <?php echo (($id_status == "1") ? "selected" : ""); ?>> Active </option>
                                                <option value="0" <?php echo (($id_status == "0") ? "selected" : ""); ?>> DeActive </option>
                                            </select>
                                        </div>

                                        <div class="col-sm-3">
                    <div class="input-group">
                    <input value="<?php echo (($search != 'NULL') ? $search : ""); ?>" id="search" class="form-control form-control-sm" type="text" placeholder="Search" aria-label="Search">
                    <div class="input-group-append">
                    <button onclick="searchfilter();" type="button" class="btn btn-sm btn-secondary" title="Search">
                      <i class="fa fa-search"></i>
                    </button>
                    <a class="btn btn-sm btn-danger ml-1" href="<?php echo base_url('Currency'); ?>" title="Clear">
                      <i class="fa fa-trash"></i>
                    </a>
                    </div>
                  </div></div>
                                       
                                    </div>
                                </div>
                            </div>
      </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
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
                                        if ($code_currency != "") {
                                            $code_currency = base64_encode($code_currency);
                                        } else {
                                            $code_currency = "NULL";
                                        }
                                        if ($name_currency != "") {
                                            $name_currency = base64_encode($name_currency);
                                        } else {
                                            $name_currency = "NULL";
                                        }
                                        if ($currency_basic_unit != "") {
                                            $currency_basic_unit = base64_encode($currency_basic_unit);
                                        } else {
                                            $currency_basic_unit = "NULL";
                                        }
                                        if ($currency_fractional_unit != "") {
                                            $currency_fractional_unit = base64_encode($currency_fractional_unit);
                                        } else {
                                            $currency_fractional_unit = "NULL";
                                        }
                                        if ($created_pesron != "") {
                                            $created_pesron = base64_encode($created_pesron);
                                        } else {
                                            $created_pesron = "NULL";
                                        }
                                        if ($order != "") {
                                            $order = $order;
                                        } else {
                                            $order = "NULL";
                                        }
                                        ?>
                                        <th scope="col">S.NO.</th>
                                        <th scope="col"><a href="<?php echo base_url('Currency/index/' . $code_currency . '/' . $name_currency . '/' . $currency_basic_unit . '/' . $currency_fractional_unit . '/' . $created_pesron . '/' .  $id_status . '/' . $search . '/' . 'msc.currency_code' . '/' . $order) ?>">CURRENCY CODE</a></th>
                                        <th scope="col"><a href="<?php echo base_url('Currency/index/' . $code_currency . '/' . $name_currency . '/' . $currency_basic_unit . '/' . $currency_fractional_unit . '/' . $created_pesron . '/' .  $id_status . '/' . $search . '/' . 'msc.currency_name' . '/' . $order) ?>">CURRENCY NAME</a></th>
                                        <th scope="col"><a href="<?php echo base_url('Currency/index/' . $code_currency . '/' . $name_currency . '/' . $currency_basic_unit . '/' . $currency_fractional_unit . '/' . $created_pesron . '/' .  $id_status . '/' . $search . '/' . 'msc.currency_basic_unit' . '/' . $order) ?>">CURRENCY BASIC UNIT</a></th>
                                        <th scope="col"><a href="<?php echo base_url('Currency/index/' . $code_currency . '/' . $name_currency . '/' . $currency_basic_unit . '/' . $currency_fractional_unit . '/' . $created_pesron . '/' .  $id_status . '/' . $search . '/' . 'msc.currency_fractional_unit' . '/' . $order) ?>">CURRENCY FRACTIONAL UNIT</a></th>
                                        <th scope="col"><a href="<?php echo base_url('Currency/index/' . $code_currency . '/' . $name_currency . '/' . $currency_basic_unit . '/' . $currency_fractional_unit . '/' . $created_pesron . '/' .  $id_status . '/' . $search . '/' . 'msc.exchange_rate' . '/' . $order) ?>">CURRENCY EXCHANGE RATE</a></th>
                                        <th scope="col"><a href="<?php echo base_url('Currency/index/' . $code_currency . '/' . $name_currency . '/' . $currency_basic_unit . '/' . $currency_fractional_unit . '/' . $created_pesron . '/' .  $id_status . '/' . $search . '/' . 'ap.admin_fname' . '/' . $order) ?>">CREATED BY</a></th>
                                        <th scope="col"><a href="<?php echo base_url('Currency/index/' . $code_currency . '/' . $name_currency . '/' . $currency_basic_unit . '/' . $currency_fractional_unit . '/' . $created_pesron . '/' .  $id_status . '/' . $search . '/' . 'msc.created_on' . '/' . $order) ?>">CREATED ON</a></th>
                                        <?php if ((exist_val('Currency/currency_status', $this->session->userdata('permission')))) { ?>
                                            <th scope="col">STATUS</th>
                                        <?php } ?>
                                        <?php if ((exist_val('Currency/fetch_currency_for_edit', $this->session->userdata('permission'))) || (exist_val('Currency/get_currency_log', $this->session->userdata('permission'))) || (exist_val('Currency/get_exchange_rate', $this->session->userdata('permission'))) ) { ?>
                                            <th scope="col" class="text-center">ACTION</th>
                                        <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $sn = $this->uri->segment('11') + 1;
                                    if ($crncy_list) {
                                        foreach ($crncy_list as $key => $value) { ?>
                                            <tr>
                                                <td><?php echo $sn; ?></td>
                                                <td><?php echo $value->currency_code ?></td>
                                                <td><?php echo $value->currency_name ?></td>
                                                <td><?php echo $value->currency_basic_unit ?></td>
                                                <td><?php echo $value->currency_fractional_unit ?></td>
                                                <td><?php echo $value->exchange_rate ?></td>
                                                <td><?php echo $value->admin_fname ?></td>
                                                <td><?php echo $value->created_on ?></td>
                                                <?php if ((exist_val('Currency/currency_status', $this->session->userdata('permission')))) { ?>
                                                    <?php if ($value->status == 1) { ?>
                                                        <td><span class="btn btn-sm btn-success status_chng" id="active" data-id="<?php echo $value->currency_id; ?>">Active</span></td>
                                                    <?php } else { ?>
                                                        <td><span class="btn btn-sm btn-danger status_chng" id="deactive" data-id="<?php echo $value->currency_id; ?>">Deactive</span></td>
                                                    <?php } ?>
                                                <?php } ?>
                                                <td>
                                                    <?php if ((exist_val('Currency/fetch_currency_for_edit', $this->session->userdata('permission')))) { ?>
                                                        <button type="button" class="btn btn-sm  edit_crncy" title="Edit Currency" data-bs-toggle="modal" data-bs-target="#crncy_edit" data-id="<?php echo $value->currency_id ?>">
                                                            <i class="fa fa-edit" title="Edit Currency"></i>
                                                        </button>
                                                    <?php } ?>

                                                    <?php if ((exist_val('Currency/get_currency_log', $this->session->userdata('permission')))) { ?>
                                                        <a href="javascript:void(0)" data-id="<?php echo $value->currency_id; ?>" class="btn log_view" data-bs-toggle="modal" data-bs-target="#exampleModal" title="Log View"><i class="fa fa-eye" title="View Log"></i></a>
                                                    <?php } ?>

                                                    <?php if ((exist_val('Currency/get_exchange_rate', $this->session->userdata('permission')))) { ?>
                                                        <a href="javascript:void(0)" data-id="<?php echo $value->currency_id; ?>" class="btn excng_rate_modal" data-bs-toggle="modal" data-bs-target=".excng_rate" title="Exchange Rate"><i class="fa-solid fa-arrow-right-arrow-left"></i></a>
                                                    <?php } ?>
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
                <?php if ($crncy_list && count($crncy_list) > 0) { ?>
                    <span><?php echo $links ?></span>
                    <span><?php echo $result_count; ?></span>
                <?php } else { ?>
                    <h3 class="text-center">NO RECORD FOUND</h3>
                <?php } ?>
            </div>
        </div>
        <!-- container fluid end -->
    </section>
</div>

<!-- ADD CURRENCY MODAL -->
<div class="modal fade add_crncy" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content modal-lg">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">ADD CURRENCY</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="javascript:void(0)" id="crncy_add">
                <input type="hidden" class="txt_csrfname" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                <div class="modal-body mx-3">
                    <div class="row">
                        <div class="col-sm-6">
                            <label data-error="wrong" data-success="right" for="currency-name">Currency Name</label>
                            <input type="text" id="currency_code" name="currency_code" class="form-control validate">

                            <label data-error="wrong" data-success="right" for="currency-basic-unit">Basic Unit</label>
                            <input type="text" id="currency_basic_unit" name="currency_basic_unit" class="form-control validate">

                            <label data-error="wrong" data-success="right" for="currency-exchange-rate">Exchange Rate</label>
                            <input type="number" id="exchange_rate" name="exchange_rate" class="form-control validate" min="0">

                            <label data-error="wrong" data-success="right" for="currency-status">Status</label>
                            <select class="form-control custom-select" id="status" name="status" aria-invalid="false" aria-="true">
                                <option value="">Choose Status</option>
                                <option value="1">Active</option>
                                <option value="0">In-Active</option>
                            </select>
                        </div>

                        <div class="col-sm-6">
                            <label data-error="wrong" data-success="right" for="currency-code">Currency Code</label>
                            <input type="text" id="currency_name" name="currency_name" class="form-control validate">

                            <label data-error="wrong" data-success="right" for="currency-fractional-unit">Fractional Unit</label>
                            <input type="text" id="currency_fractional_unit" name="currency_fractional_unit" class="form-control validate">

                            <label data-error="wrong" data-success="right" for="currency-decimal">Currency Decimal</label>
                            <input type="number" id="currency_decimal" name="currency_decimal" class="form-control validate" min="0">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">CLOSE</button>
                    <button type="submit" class="btn btn-success">ADD</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- END -->

<!-- EDIT CURRENCY MODAL -->
<div class="modal fade" id="crncy_edit" tabindex="-1" role="dialog" aria-labelledby="crncy_editLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content modal-lg">
            <div class="modal-header">
                <h5 class="modal-title" id="crncy_editLabel">EDIT CURRENCY DETAILS</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" id="edit_crncy">
                <div class="modal-body">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <input type="hidden" id="currency_id" name="currency_id" value="">
                    <div class="row">
                        <div class="col-sm-6">
                            <label data-error="wrong" data-success="right" for="currency-name">Currency Name</label>
                            <input type="text" id="currency_code_edit" name="currency_code" class="form-control validate">

                            <label data-error="wrong" data-success="right" for="currency-basic-unit">Basic Unit</label>
                            <input type="text" id="currency_basic_unit_edit" name="currency_basic_unit" class="form-control validate">

                            <label data-error="wrong" data-success="right" for="currency-exchange-rate">Exchange Rate</label>
                            <input type="number" id="exchange_rate_edit" name="exchange_rate" class="form-control validate" min="0">
                        </div>

                        <div class="col-sm-6">
                            <label data-error="wrong" data-success="right" for="currency-code">Currency Code</label>
                            <input type="text" id="currency_name_edit" name="currency_name" class="form-control validate">

                            <label data-error="wrong" data-success="right" for="currency-fractional-unit">Fractional Unit</label>
                            <input type="text" id="currency_fractional_unit_edit" name="currency_fractional_unit" class="form-control validate">

                            <label data-error="wrong" data-success="right" for="currency-decimal">Currency Decimal</label>
                            <input type="number" id="currency_decimal_edit" name="currency_decimal" class="form-control validate" min="0">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success"> Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- END -->

<!-- EXCHANGE CURRENCY MODAL -->
<div class="modal fade excng_rate" tabindex="-1" role="dialog" aria-labelledby="exchangeRateLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content modal-lg">
            <div class="modal-header">
                <h5 class="modal-title" id="exchangeRateLabel">EXCHANGE CURRENCY DETAILS</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="javascript:void(0)" id="exchng_currency">
                <input type="hidden" class="txt_csrfname" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                <input type="hidden" id="currency_id" name="currency_id" value="">
                <input type="hidden" id="exchange_id" name="currency_ex_id" value="">
                <div class="modal-body mx-3">
                    <div class="row">
                        <div class="col-sm-6">
                            <label data-error="wrong" data-success="right" for="currency-name">Select Currency</label>
                            <select name="ex_curr_id" id="currency_name_exchng" class="form-control form-control-sm">
                                <option value="">Select Currency</option>
                                <?php foreach ($cn_name as $cnn) { ?>
                                    <option value="<?php echo $cnn->currency_id; ?>"> <?php echo $cnn->currency_name; ?> </option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="col-sm-6">
                            <label data-error="wrong" data-success="right" for="currency-exchange-rate">Exchange Rate</label> 
                            <input type="number" id="exchange_rate_append" name="ex_rate" class="form-control validate" min="0" step="any" value="">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">CLOSE</button>
                    <button type="submit" class="btn btn-success">ADD</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- END -->

<!-- Currency Log Modal Starts -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Currency Log</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>SL.No.</th>
                            <th>Operation</th>
                            <th>Action</th>
                            <th>Performed By</th>
                            <th>Performed at</th>
                        </tr>
                    </thead>
                    <tbody id="currency_log"></tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- Currency Log Modal Ends -->

<script>
    function searchfilter() {
        var url = '<?php echo base_url("Currency/index"); ?>';
        var code_currency = $('#currency_code').val();
        if (code_currency != "") {
            url = url + '/' + btoa(code_currency);
        } else {
            code_currency = "";
            url = url + '/NULL';
        }

        var name_currency = $('#currency_name').val();
        if (name_currency != "") {
            url = url + '/' + btoa(name_currency);
        } else {
            name_currency = "";
            url = url + '/NULL';
        }

        var basic_unit = $('#currency_basic_unit').val();
        if (basic_unit != "") {
            url = url + '/' + btoa(basic_unit);
        } else {
            basic_unit = "";
            url = url + '/NULL';
        }

        var fractional_unit = $('#currency_fractional_unit').val();
        if (fractional_unit != "") {
            url = url + '/' + btoa(fractional_unit);
        } else {
            fractional_unit = "";
            url = url + '/NULL';
        }

        var created_pesron = $('#created_by').val();
        if (created_pesron != "") {
            url = url + '/' + btoa(created_pesron);
        } else {
            created_pesron = "";
            url = url + '/NULL';
        }

        var id_status = $('#status').val();
        if (id_status != "") {
            url = url + '/' + btoa(id_status);
        } else {
            id_status = "";
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

    $(document).ready(function() {
        var style = {
            'margin': '0 auto'
        };
        $('.modal-content').css(style);

        $('#crncy_add').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                url: "<?php echo base_url('Currency/add_currency') ?>",
                method: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    var result = $.parseJSON(response);
                    if (result.status > 0) {
                        $('.add_crncy').modal('hide');
                        $("#crncy_add").trigger('reset');
                        window.location.reload();
                    } else {
                        $.notify(result.msg, 'error');
                    }
                    if (result.error) {
                        var error = result.error;
                        $('.form_errors').remove();
                        $.each(error, function(i, v) {
                            $('#crncy_add select[name="' + i + '"]').after('<span class="text-danger form_errors">' + v + '</span>');
                            $('#crncy_add input[name="' + i + '"]').after('<span class="text-danger form_errors">' + v + '</span>');
                        });

                    } else {
                        $('.form_errors').remove();
                    }
                }
            });
            return false;
        });

        // Edit Currency
        $('.edit_crncy').on('click', function() {
            var currency_id = $(this).data('id');
            get_crncy_edit_data(currency_id);
            $('#edit_crncy #currency_id').val(currency_id);
        });

        function get_crncy_edit_data(currency_id) {
            const _tokken = $('meta[name="_tokken"]').attr("value");
            $.ajax({
                url: "<?php echo base_url('Currency/fetch_currency_for_edit') ?>",
                method: "POST",
                data: {
                    currency_id: currency_id,
                    _tokken: _tokken
                },
                success: function(response) {
                    var data = $.parseJSON(response);
                    if (data) {
                        $('#crncy_edit #currency_code_edit').val(data.currency_code);
                        $('#crncy_edit #currency_name_edit').val(data.currency_name);
                        $('#crncy_edit #currency_basic_unit_edit').val(data.currency_basic_unit);
                        $('#crncy_edit #currency_fractional_unit_edit').val(data.currency_fractional_unit);
                        $('#crncy_edit #exchange_rate_edit').val(data.exchange_rate);
                        $('#crncy_edit #currency_decimal_edit').val(data.currency_decimal);
                    }
                }
            });
            return false;
        }

        // update currency details
        $('#edit_crncy').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                url: "<?php echo base_url('Currency/update_currency') ?>",
                method: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    var result = $.parseJSON(response);
                    if (result.status > 0) {
                        $('#crncy_edit').modal('hide');
                        $("#edit_crncy").trigger('reset');
                        window.location.reload();
                    } else {
                        $.notify(result.msg, 'error');
                    }
                    if (result.error) {
                        var error = result.error;
                        $('.form_errors').remove();
                        $.each(error, function(i, v) {
                            $('#edit_crncy input[name="' + i + '"]').after('<span class="text-danger form_errors">' + v + '</span>');
                            $('#edit_crncy select[name="' + i + '"]').after('<span class="text-danger form_errors">' + v + '</span>');
                        });

                    } else {
                        $('.form_errors').remove();
                    }
                }
            });
            return false;
        });
        // END

        // changing status of currency
        $('.status_chng').click(function() {
            var currency_id = $(this).attr("data-id");
            var _tokken = $('meta[name="_tokken"]').attr('value');
            $.ajax({
                url: "<?php echo base_url(); ?>Currency/currency_status",
                data: {
                    "currency_id": currency_id,
                    "_tokken": _tokken
                },
                type: 'post',
                success: function(result) {
                    location.reload();
                }
            });
        });
        // END

        // Currency Log
        $('.log_view').click(function(e) {
            e.preventDefault();
            $('#currency_log').empty();
            var currency_id = $(this).data('id');
            const _tokken = $('meta[name="_tokken"]').attr("value");
            $.ajax({
                type: 'post',
                url: "<?php echo base_url('Currency/get_currency_log') ?>",
                data: {
                    _tokken: _tokken,
                    currency_id: currency_id
                },
                success: function(data) {
                    var data = $.parseJSON(data);
                    if (data) {
                        sn = 1;
                        $.each(data, function(index, log) {
                            var value = '';
                            value += '<tr>';
                            value += '<td>' + sn + '</td>';
                            value += '<td>' + log.action_taken + '</td>';
                            value += '<td>' + log.text + '</td>';
                            value += '<td>' + log.taken_by + '</td>';
                            value += '<td>' + log.taken_at + '</td>';
                            value += '</tr>';
                            $('#currency_log').append(value);
                            sn++;
                        });
                    } else {
                        var value = '';
                        value += '<tr>';
                        value += '<td colspan="5">';
                        value += "<h4> NO RECORD FOUND! </h4>";
                        value += "</td>";
                        value += "</tr>";
                        $('#currency_log').append(value);
                    }
                }
            });
            // return false;
        });
        //ends

        // Exchange Currency 
        $('.excng_rate_modal').on('click', function(){
            var currency_id = $(this).data('id');      
            $('#exchng_currency #currency_id').val(currency_id);           
        });

        $('#currency_name_exchng').on('change', function(){
            var currency_id = $('#exchng_currency #currency_id').val();
            var new_currency_id = $('#currency_name_exchng :selected').val();
            const _tokken = $('meta[name="_tokken"]').attr("value");
            $.ajax({
                url: "<?php echo base_url('Currency/fetch_currency_exchange_details') ?>",
                method : "POST",
                data: {
                    currency_id : currency_id,
                    ex_curr_id : new_currency_id,
                    _tokken: _tokken
                },
                success: function(response) {
                    var data = $.parseJSON(response);
                    $('#exchng_currency #exchange_rate_append').val("");
                    if(data){
                        $('#exchng_currency #exchange_rate_append').val(data.ex_rate);
                        $('#exchng_currency #exchange_id').val(data.ex_id);
                    }
                }

            })
            return false;
        });

        // add or update currency exchnage values
        $('#exchng_currency').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                url: "<?php echo base_url('Currency/add_update_exch_currency') ?>",
                method: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    var result = $.parseJSON(response);
                    if (result.status > 0) {
                        $('.excng_rate_modal').modal('hide');
                        $("#exchng_currency").trigger('reset');
                        window.location.reload();
                    } else {
                        $.notify(result.msg, 'error');
                    }
                    if (result.error) {
                        var error = result.error;
                        $('.form_errors').remove();
                        $.each(error, function(i, v) {
                            $('#exchng_currency select[name="' + i + '"]').after('<span class="text-danger form_errors">' + v + '</span>');
                            // $('#exchng_currency input[name="' + i + '"]').after('<span class="text-danger form_errors">' + v + '</span>');
                        });

                    } else {
                        $('.form_errors').remove();
                    }
                }
            });
            return false;
        });
    });
</script>