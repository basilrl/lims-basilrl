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
                    <h1>REPORT GENERATED</h1>
                </div>
            </div>

            <hr>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-sm-12">
                                    <h6 style="text-align: center;"><b>REPORT PARAMETERS</b></h6>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3">
                                    <label for="">FROM :</label>
                                    <input type="date" class="form-control form-control-sm from_date" value="<?php echo ($from_date) ? $from_date : ""; ?>">
                                </div>
                                <div class="col-sm-3">
                                    <label for="">TO :</label>
                                    <input type="date" class="form-control form-control-sm to_date" value="<?php echo $to_date; ?>">
                                </div>

                                <div class="col-sm-3">
                                    <label for="">REPORT NUMBER</label>
                                    <input type="hidden" class="report_number_hidden" value="<?php echo ($report_number) ? $report_number : ""; ?>">
                                    <input type="text" class="form-control form-control-sm report_number" value="<?php echo ($report_number) ? $report_number : ""; ?>" autocomplete="off" placeholder="Report Number">
                                    <ul class="list-group-item report_number_list" style="display:none">
                                    </ul>
                                </div>

                                <div class="col-sm-3">
                                    <label for="">CUSTOMERS :</label>
                                    <input type="hidden" class="customer_id" value="<?php echo ($customer_id) ? $customer_id : ""; ?>">
                                    <input type="text" class="form-control form-control-sm customer_name"  placeholder="Customer" autocomplete="off" value="<?php echo ($customer_name) ? $customer_name : ""; ?>">
                                    <ul class="list-group-item customer_list" style="display:none">
                                    </ul>
                                </div>

                            </div>

                            <div class="row">
                               
                                <div class="col-sm-3">
                                    <label for="">BUYER :</label>
                                    <input type="hidden" class="buyer_id_hidden" value="<?php echo ($buyer_id) ? $buyer_id : ""; ?>">
                                    <input type="text" class="form-control form-control-sm buyer_name_trf"  placeholder="Buyer" autocomplete="off" value="<?php echo ($buyer_name) ? $buyer_name : ""; ?>">
                                    <ul class="list-group-item buyer_list" style="display:none">
                                    </ul>

                                </div>
                                <div class="col-sm-3">
                                    <label for="">AGENT :</label>
                                    <input type="hidden" class="agent_id_hidden" value="<?php echo ($agent_id) ? $agent_id : ""; ?>">
                                    <input type="text" class="form-control form-control-sm agent_name_trf"  placeholder="Agent" autocomplete="off" value="<?php echo ($agent_name) ? $agent_name : ""; ?>">
                                    <ul class="list-group-item agent_list" style="display:none">
                                    </ul>
                                </div>

                                <div class="col-sm-3">
                                    <label for="">THIRD PARTY :</label>
                                    <input type="hidden" class="thirdparty_id_hidden" value="<?php echo ($thirdparty_id) ? $thirdparty_id : ""; ?>">
                                    <input type="text" class="form-control form-control-sm thirdparty_name_trf"  placeholder="Thirdparty" autocomplete="off" value="<?php echo ($thirdparty_name) ? $thirdparty_name : ""; ?>">
                                    <ul class="list-group-item thirdparty_list" style="display:none">
                                    </ul>
                                </div>

                                <div class="col-sm-3">
                                    <label for="">PRODUCT :</label>
                                    <input type="hidden" class="product_id_hidden" value="<?php echo ($product_id) ? $product_id : ""; ?>">
                                    <input type="text" class="form-control form-control-sm product_name_trf"  placeholder="Product" autocomplete="off" value="<?php echo ($product_name) ? $product_name : ""; ?>">
                                    <ul class="list-group-item product_list" style="display:none">
                                    </ul>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-3">
                                    <label for="">COUNTRY OF ORIGIN :</label>
                                    <input type="hidden" class="country_id_hidden" value="<?php echo ($country_id) ? $country_id : ""; ?>">
                                    <input type="text" class="form-control form-control-sm country_name_trf"  placeholder="Country" autocomplete="off" value="<?php echo ($country_name) ? $country_name : ""; ?>">
                                    <ul class="list-group-item country_list" style="display:none">
                                    </ul>
                                </div>
                                <div class="col-sm-6">
                                    <label for="">SEARCH :</label>
                                    <input type="search" class="form-control form-control-sm search" value="<?php echo ($search) ? $search : ""; ?>" placeholder="Search...">
                                </div>
                                <div class="col-sm-3">
                                    <label for="" style="display: block;">ACTION :</label>
                                    <button type="button" class="btn btn-sm btn-default" title="GENERATE REPORT" onclick="search_filter_record()" >
                                        <img src="<?php echo base_url('assets/images/email_open.png') ?>" alt="generate">
                                    </button>
                                    <a type="button" class="btn btn-sm btn-default" title="RESET PARAMETERS" href="<?php echo base_url('Report_generated') ?>">
                                        <img src="<?php echo base_url('assets/images/arrow_refresh.png') ?>" alt="generate">
                                    </a>
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                                    

                            <?php if(exist_val('Report_generated/excel_export_generated_report',$this->session->userdata('permission'))){ ?>

                                    <a type="button" class="btn btn-sm btn-default" title="EXPORT REPORT" href="<?php echo base_url('Report_generated/excel_export_generated_report')?>">
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

                                        if ($report_number != "") {
                                            $report_number = base64_encode($report_number);
                                        } else {
                                            $report_number = "NULL";
                                        }


                                        if ($customer_name != "") {
                                            $customer_name = base64_encode($customer_name);
                                        } else {
                                            $customer_name = "NULL";
                                        }
                                        
                                        if ($buyer_name != "") {
                                            $buyer_name = base64_encode($buyer_name);
                                        } else {
                                            $buyer_name = "NULL";
                                        }

                                        if ($agent_name != "") {
                                            $agent_name = base64_encode($agent_name);
                                        } else {
                                            $agent_name = "NULL";
                                        }

                                        

                                        if ($thirdparty_name != "") {
                                            $thirdparty_name = base64_encode($thirdparty_name);
                                        } else {
                                            $thirdparty_name = "NULL";
                                        }


                                        if ($product_name != "") {
                                            $product_name = base64_encode($product_name);
                                        } else {
                                            $product_name = "NULL";
                                        }

                                        if ($country_name != "") {
                                            $country_name = base64_encode($country_name);
                                        } else {
                                            $country_name = "NULL";
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

                                        <th scope="col"><a href="<?php echo base_url('Report_generated/index/'.$report_number.'/'.$customer_name.'/'.$buyer_name.'/'.$agent_name.'/'.$thirdparty_name.'/'.$product_name.'/'.$country_name.'/'.$from_date.'/'.$to_date.'/'.$search.'/'.base64_encode('rp.report_num').'/'.$order)?>">REPORT NUMBER</a></th>

                                        <th scope="col"><a href="<?php echo base_url('Report_generated/index/'.$report_number.'/'.$customer_name.'/'.$buyer_name.'/'.$agent_name.'/'.$thirdparty_name.'/'.$product_name.'/'.$country_name.'/'.$from_date.'/'.$to_date.'/'.$search.'/'.base64_encode('rp.generated_date').'/'.$order)?>">REPORT DATE</a></th>

                                        <th scope="col"><a href="<?php echo base_url('Report_generated/index/'.$report_number.'/'.$customer_name.'/'.$buyer_name.'/'.$agent_name.'/'.$thirdparty_name.'/'.$product_name.'/'.$country_name.'/'.$from_date.'/'.$to_date.'/'.$search.'/'.base64_encode('sample.sample_type_name').'/'.$order)?>">PRODUCT</a></th>

                                        <th scope="col"><a href="<?php echo base_url('Report_generated/index/'.$report_number.'/'.$customer_name.'/'.$buyer_name.'/'.$agent_name.'/'.$thirdparty_name.'/'.$product_name.'/'.$country_name.'/'.$from_date.'/'.$to_date.'/'.$search.'/'.base64_encode('cust.customer_name').'/'.$order)?>">CUSTOMER NAME</a></th>

                                        <th scope="col"><a href="<?php echo base_url('Report_generated/index/'.$report_number.'/'.$customer_name.'/'.$buyer_name.'/'.$agent_name.'/'.$thirdparty_name.'/'.$product_name.'/'.$country_name.'/'.$from_date.'/'.$to_date.'/'.$search.'/'.base64_encode('buyer.customer_name').'/'.$order)?>">BUYER</a></th>

                                        <th scope="col"><a href="<?php echo base_url('Report_generated/index/'.$report_number.'/'.$customer_name.'/'.$buyer_name.'/'.$agent_name.'/'.$thirdparty_name.'/'.$product_name.'/'.$country_name.'/'.$from_date.'/'.$to_date.'/'.$search.'/'.base64_encode('agent.customer_name').'/'.$order)?>">AGENT</a></th>

                                        <th scope="col"><a href="<?php echo base_url('Report_generated/index/'.$report_number.'/'.$customer_name.'/'.$buyer_name.'/'.$agent_name.'/'.$thirdparty_name.'/'.$product_name.'/'.$country_name.'/'.$from_date.'/'.$to_date.'/'.$search.'/'.base64_encode('thirdparty.customer_name').'/'.$order)?>">THIRD PARTY</a></th>

                                        <th scope="col"><a href="<?php echo base_url('Report_generated/index/'.$report_number.'/'.$customer_name.'/'.$buyer_name.'/'.$agent_name.'/'.$thirdparty_name.'/'.$product_name.'/'.$country_name.'/'.$from_date.'/'.$to_date.'/'.$search.'/'.base64_encode('rp.status').'/'.$order)?>">STATUS</a></th>

                                        <th scope="col"><a href="<?php echo base_url('Report_generated/index/'.$report_number.'/'.$customer_name.'/'.$buyer_name.'/'.$agent_name.'/'.$thirdparty_name.'/'.$product_name.'/'.$country_name.'/'.$from_date.'/'.$to_date.'/'.$search.'/'.base64_encode('country.country_name').'/'.$order)?>">COUNTRY OF ORIGIN</a></th>

                                        <th scope="col"><a href="<?php echo base_url('Report_generated/index/'.$report_number.'/'.$customer_name.'/'.$buyer_name.'/'.$agent_name.'/'.$thirdparty_name.'/'.$product_name.'/'.$country_name.'/'.$from_date.'/'.$to_date.'/'.$search.'/'.base64_encode('rp.report_generated_by').'/'.$order)?>">CREATED BY</a></th>

                                      

                                     </tr> 
                                </thead>

                                <tbody>

                                     <?php $sn = $this->uri->segment('15') + 1;
                                    if ($report_list) {
                                        //   $quotes_list = $quotes_list[0];
                                        foreach ($report_list as $key => $item) { ?>
                                            <tr>
                                                <td><?php echo $sn; ?></td>
                                                <td><?php echo $item->report_num ?></td>
                                                <td><?php echo $item->report_date ?></td>
                                                <td><?php echo $item->product_name ?></td>
                                                <td><?php echo $item->customer_name ?></td>
                                                <td><?php echo $item->buyer_name ?></td>
                                                <td><?php echo $item->agent_name?></td>
                                                <td><?php echo $item->thirdparty_name ?></td>
                                                <td><?php echo $item->status ?></td>
                                                <td><?php echo $item->country_name ?></td>
                                                <td><?php echo $item->created_by?></td>
                                              
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
                                 <?php if ($report_list && count($report_list) > 0) { ?>
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
        url = '<?php echo base_url("Report_generated/index"); ?>';

        if($('.report_number').val()==""){
            $('.report_number_hidden').val("");
        }
        report_number = $('.report_number_hidden').val();

        if($('.customer_name').val()==""){
            $('.customer_id').val("");
        }
        customer_id = $('.customer_id').val();

        if($('.buyer_name_trf').val()==""){
            $('.buyer_id_hidden').val("");
        }
        buyer_id_hidden = $('.buyer_id_hidden').val();

        if($('.agent_name_trf').val()==""){
            $('.agent_id_hidden').val("");
        }
        agent_id_hidden = $('.agent_id_hidden').val();

        if($('.thirdparty_name_trf').val()==""){
            $('.thirdparty_id_hidden').val("");
        }
        thirdpary_id_hidden = $('.thirdparty_id_hidden').val();

        if($('.product_name_trf').val()==""){
            $('.product_id_hidden').val("");
        }
        product_id_hidden = $('.product_id_hidden').val();

        if($('.country_name_trf').val()==""){
            $('.country_id_hidden').val("");
        }
        country_id_hidden = $('.country_id_hidden').val();


        from_date = $('.from_date').val();
        to_date = $('.to_date').val();        
        search = $('.search').val();


       
        if (report_number != "") {
            url = url + '/' + btoa(report_number);
        } else {
            report_number = "";
            url = url + '/NULL';
        }


        if (customer_id != "") {
            url = url + '/' + btoa(customer_id);
        } else {
            customer_id = "";
            url = url + '/NULL';
        }

        if (buyer_id_hidden != "") {
            url = url + '/' + btoa(buyer_id_hidden);
        } else {
            buyer_id_hidden = "";
            url = url + '/NULL';
        }

        if (agent_id_hidden != "") {
            url = url + '/' + btoa(agent_id_hidden);
        } else {
            agent_id_hidden = "";
            url = url + '/NULL';
        }

        if (thirdpary_id_hidden != "") {
            url = url + '/' + btoa(thirdpary_id_hidden);
        } else {
            thirdpary_id_hidden = "";
            url = url + '/NULL';
        }

        if (product_id_hidden != "") {
            url = url + '/' + btoa(product_id_hidden);
        } else {
            product_id_hidden = "";
            url = url + '/NULL';
        }

        if (country_id_hidden != "") {
            url = url + '/' + btoa(country_id_hidden);
        } else {
            country_id_hidden = "";
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



        // report number autosuggetion
        getAutolist_generated(
            'report_number_hidden',
            'report_number',
            'report_number_list',
            'report_number_li',
            '',
            'report_num',
            'report_num as id,report_num as name',
            'generated_reports'
        );

      

        // customer autosuggetion
        getAutolist_generated(
            'customer_id',
            'customer_name',
            'customer_list',
            'customer_li',
            '',
            'customer_name',
            'customer_id as id,customer_name as name',
            'cust_customers'
        );

         // buyer autosuggetion
         var where_buyer = JSON.stringify({
            'customer_type':'Buyer'
        });

        getAutolist_generated(
            'buyer_id_hidden',
            'buyer_name_trf',
            'buyer_list',
            'buyer_li',
            where_buyer,
            'customer_name',
            'customer_id as id,customer_name as name',
            'cust_customers'
        );

         // agent autosuggetion
         var where_agent = JSON.stringify({
            'customer_type':'Agent'
        });

        getAutolist_generated(
            'agent_id_hidden',
            'agent_name_trf',
            'agent_list',
            'agent_li',
            where_agent,
            'customer_name',
            'customer_id as id,customer_name as name',
            'cust_customers'
        );

         // thirdparty autosuggetion
         var where_thirdparty = JSON.stringify({
            'customer_type':'Thirdparty'
        });

        getAutolist_generated(
            'thirdparty_id_hidden',
            'thirdparty_name_trf',
            'thirdparty_list',
            'thirdparty_li',
            where_thirdparty,
            'customer_name',
            'customer_id as id,customer_name as name',
            'cust_customers'
        );

        // product autosuggention 

        getAutolist_generated(
            'product_id_hidden',
            'product_name_trf',
            'product_list',
            'product_li',
            '',
            'sample_type_name',
            'sample_type_id as id,sample_type_name as name',
            'mst_sample_types'
        );

        // country autosuggention 

        getAutolist_generated(
            'country_id_hidden',
            'country_name_trf',
            'country_list',
            'country_li',
            '',
            'country_name',
            'country_id as id,country_name as name',
            'mst_country'
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

        function getAutolist_generated(hide_input, input, ul, li, where, like, select, table) {

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
                var _URL = "<?php echo base_url('Report_generated/get_auto_list_report_generated') ?>";
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
                            ulEvent.append($('<li class="list-group-item ' + li + '"' + 'data-id="not">NO REORD FOUND</li>'));
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