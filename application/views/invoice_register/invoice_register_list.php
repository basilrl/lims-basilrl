<?php 
    if($to_date==""){
        $to_date = date("Y-m-d");
    }
?>

<style>
    .list-group-item {
        padding: 5px;
        list-style: none;
    }
</style>
<div class="content-wrapper">
    <section class="content-header">
        <!-- container fluid start -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1>INVOICE REGISTER</h1>
                </div>
            </div>

            <hr>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-sm-6">
                                    <h6 style="text-align: center;"> REPORT PARAMETERS</h6>
                                </div>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control form-control-sm search" placeholder="Search..." value="<?php echo ($search) ? $search : ""; ?>">
                                </div>
                            </div>
                            <hr>
                            <div class="row">

                                <label for=""><b>FROM :</b></label>
                                <div class="col-sm-2">
                                    <input type="date" class="form-control form-control-sm from_date" value="<?php echo ($from_date) ? $from_date : ""; ?>">
                                </div>

                                <label for=""><b>TO :</b></label>
                                <div class="col-sm-2">
                                    <input type="date" class="form-control form-control-sm to_date" value="<?php echo $to_date; ?>">
                                </div>

                                <div class="col-sm-2">
                                    <input type="hidden" class="invoice_hidden" value="<?php echo ($invoice_number) ? $invoice_number : ""; ?>">
                                    <input type="text" class="form-control form-control-sm invoice_number" placeholder="INVOICE NUMBER" autocomplete="off" value="<?php echo ($invoice_number) ? $invoice_number : ""; ?>">
                                    <ul class="list-group-item invoice_list" style="display:none">
                                    </ul>
                                </div>

                                <div class="col-sm-2">
                                    <input type="hidden" class="customer_id" value="<?php echo ($customer_id) ? $customer_id : ""; ?>">
                                    <input type="text" class="form-control form-control-sm customer_name" placeholder="SEARCH BY CUSTOMER" autocomplete="off" value="<?php echo ($customer_name) ? $customer_name : ""; ?>">
                                    <ul class="list-group-item customer_list" style="display:none">
                                    </ul>
                                </div>

                                <div class="col-sm-2">

                            

                                    <button type="button" class="btn btn-sm btn-default" title="GENERATE REPORT" onclick="search_filter_record()">
                                        <img src="<?php echo base_url('assets/images/email_open.png') ?>" alt="generate">
                                    </button>
                                    <a type="button" class="btn btn-sm btn-default" title="RESET PARAMETERS" href="<?php echo base_url('Invoice_register') ?>">
                                        <img src="<?php echo base_url('assets/images/arrow_refresh.png') ?>" alt="generate">
                                    </a>
                                    
                                </div>

                                <div class="col-sm-1">

                                <?php if(exist_val('Invoice_register/excel_export_reports',$this->session->userdata('permission'))){ ?>
                                    <a type="button" class="btn btn-sm btn-default" title="EXPORT REPORT" href="<?php echo base_url('Invoice_register/excel_export_reports')?>">
                                        <img src="<?php echo base_url('assets/images/page_excel.png') ?>" alt="EXPORT">
                                    </a>
                                    <?php }?>
                                </div>

                            </div>
                        </div>
                        <!-- end card header -->

                        <div class="table-responsive p-2">
                            <table class="table table-sm" style="font-size: small;">
                                <thead>
                                    <tr>


                                    <?php
                                        if ($search) {
                                            $search = base64_encode($search);
                                        } else {
                                            $search = "NULL";
                                        }
                                       
                                        if ($invoice_number != "") {
                                            $invoice_number = base64_encode($invoice_number);
                                        } else {
                                            $invoice_number = "NULL";
                                        }
                                        if ($customer_name != "") {
                                            $customer_name = base64_encode($customer_name);
                                        } else {
                                            $customer_name = "NULL";
                                        } 
                                        if ($order != "") {
                                            $order = $order;
                                        } else {
                                            $order = "NULL";
                                        }
                                        if ($from_date != "") {
                                            $from_date = base64_encode($from_date);
                                        } else {
                                            $from_date = "NULL";
                                        }
                                        if ($to_date != "") {
                                            $to_date = base64_encode($to_date);
                                        } else {
                                            $to_date = "NULL";
                                        }
                                    ?>

                                        <th scope="col">S. NO.</th>
                                        <th scope="col"><a href="<?php echo base_url('Invoice_register/index/'.$invoice_number.'/'.$customer_name.'/'.$from_date.'/'.$to_date.'/'.$search.'/'.base64_encode('invoice.proforma_invoice_number').'/'.$order)?>">INVOICE NUMBER</a></th>

                                        <th scope="col"><a href="<?php echo base_url('Invoice_register/index/'.$invoice_number.'/'.$customer_name.'/'.$from_date.'/'.$to_date.'/'.$search.'/'.base64_encode('invoice.proforma_invoice_date').'/'.$order)?>">INVOICE DATE</a></th>

                                        <th scope="col"><a href="<?php echo base_url('Invoice_register/index/'.$invoice_number.'/'.$customer_name.'/'.$from_date.'/'.$to_date.'/'.$search.'/'.base64_encode('cust.customer_name').'/'.$order)?>">CUSTOMER NAME</a></th>

                                        <th scope="col"><a href="<?php echo base_url('Invoice_register/index/'.$invoice_number.'/'.$customer_name.'/'.$from_date.'/'.$to_date.'/'.$search.'/'.base64_encode('invoice.total_amount').'/'.$order)?>">TOTAL AMOUNT</a></th>

                                        <th scope="col"><a href="<?php echo base_url('Invoice_register/index/'.$invoice_number.'/'.$customer_name.'/'.$from_date.'/'.$to_date.'/'.$search.'/'.base64_encode('currency_name').'/'.$order)?>">CURRENCY</a></th>

                                        <th scope="col"><a href="<?php echo base_url('Invoice_register/index/'.$invoice_number.'/'.$customer_name.'/'.$from_date.'/'.$to_date.'/'.$search.'/'.base64_encode('invoice.customer_type').'/'.$order)?>">CUSTOMER TYPE</a></th>

                                        <th scope="col"><a href="<?php echo base_url('Invoice_register/index/'.$invoice_number.'/'.$customer_name.'/'.$from_date.'/'.$to_date.'/'.$search.'/'.base64_encode('invoice.proforma_invoice_job_type').'/'.$order)?>">JOB TYPE</a></th>

                                        <th scope="col"><a href="<?php echo base_url('Invoice_register/index/'.$invoice_number.'/'.$customer_name.'/'.$from_date.'/'.$to_date.'/'.$search.'/'.base64_encode('invoice.created_by').'/'.$order)?>">CREATED BY</a></th>

                                    </tr>
                                </thead>

                                <tbody>

                                    <?php $sn = $this->uri->segment('10') + 1;
                                    if ($invoice_register_list) {
                                        //   $quotes_list = $quotes_list[0];
                                        foreach ($invoice_register_list as $key => $item) { ?>
                                            <tr>
                                                <td><?php echo $sn; ?></td>
                                                <td><?php echo $item->proforma_invoice_number ?></td>
                                                <td><?php echo $item->proforma_invoice_date ?></td>
                                                <td><?php echo $item->customer_name ?></td>
                                                <td><?php echo $item->total_amount ?></td>
                                                <td><?php echo $item->currency_name ?></td>
                                                <td><?php echo $item->customer_type ?></td>
                                                <td><?php echo $item->proforma_invoice_job_type ?></td>
                                                <td><?php echo $item->created_by ?></td>

                                            </tr>
                                    <?php $sn++;
                                        }
                                    } ?>
                                </tbody>
                            </table>
                        </div>

                        <hr>

                        <div class="card-header">

                                <div class="row">
                                 <?php if ($invoice_register_list && count($invoice_register_list) > 0) { ?>
                                    <div class="col-sm-6">
                                         <span><?php echo $result_count; ?></span>
                                    </div>
                                    <div class="col-sm-6">
                                         <span><?php echo $links ?></span>
                                    </div>
                                    <?php } else { ?>
                                        <div class="col-sm-12">
                                        <h6>NO RECORD FOUND!</h6>
                                        </div>
                                    <?php } ?>
                                </div>
                            
                        </div>
                    </div>
                    <!-- card end -->
                </div>
            </div>

            <!-- menu end -->


        </div>
        <!-- container fluid end -->
    </section>
</div>

<script>
    function search_filter_record() {
        url = '<?php echo base_url("Invoice_register/index"); ?>';
        if($('.invoice_number').val()==""){
            $('.invoice_hidden').val("");
        }
        invoice_number = $('.invoice_hidden').val();

        if($('.customer_name').val()==""){
            $('.customer_id').val("");
        }
        customer_id = $('.customer_id').val();
        from_date = $('.from_date').val();
        to_date = $('.to_date').val();
        search = $('.search').val();

        if (invoice_number != "") {
            url = url + '/' + btoa(invoice_number);
        } else {
            invoice_number = "";
            url = url + '/NULL';
        }
        
        if (customer_id != "") {
            url = url + '/' + btoa(customer_id);
        } else {
            customer_id = "";
            url = url + '/NULL';
        }
        if (from_date != "") {
            url = url + '/' + btoa(from_date);
        } else {
            from_date = "";
            url = url + '/NULL';
        }
        if (to_date != "") {
            url = url + '/' + btoa(to_date);
        } else {
            to_date = "";
            url = url + '/NULL';
        }

        if (search != "") {
            url = url + '/' + btoa(search);
        } else {
            search = "";
            url = url + '/NULL';
        }

        window.location.href = url;
    }
</script>
<script>
    $(document).ready(function() {



        // invoice number auto suggetion
        getAutolist_report(
            'invoice_hidden',
            'invoice_number',
            'invoice_list',
            'invoice_li',
            '',
            'proforma_invoice_number',
            'proforma_invoice_number as id,proforma_invoice_number as name',
            'invoice_proforma'
        );

        // customers auto suggetions
        getAutolist_report(
            'customer_id',
            'customer_name',
            'customer_list',
            'customer_li',
            '',
            'customer_name',
            'customer_id as id,customer_name as name',
            'cust_customers'
        );




        var css = {
            "position": "absolute",
            "width": "93%",
            "height": "auto",
            "font-size": "10px",
            "z-index": 999,
            "overflow-y": "auto",
            "overflow-x": "hidden",
            "max-height": "200px",
            "cursor": "pointer",
        };

        function getAutolist_report(hide_input, input, ul, li, where, like, select, table) {

            var base_url = $("body").attr("data-url");
            var hide_inputEvent = $("input." + hide_input);
            var inputEvent = $("input." + input);
            var ulEvent = $("ul." + ul);

            inputEvent.focusout(function() {
                ulEvent.fadeOut();
            });

            inputEvent.on("keyup", function(e) {
                var me = $(this);
                var key = $(this).val();
                var _URL = "<?php echo base_url('Invoice_register/get_auto_list_invoice') ?>";
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
                                ulEvent.append($('<li class="list-group-item ' + li + '"' + "data-id=" + value.id + ">" + value.name + "<li>"));
                            });
                        } else {
                            ulEvent.append($('<li class="list-group-item ' + li + '"' + 'data-id="">NO REORD FOUND</li>'));
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

                return false;

            });
        }
    })
</script>