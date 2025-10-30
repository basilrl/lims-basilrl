

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
                    <h1>ANALYST PERFORMANCE REPORT</h1>
                </div>
            </div>

            <hr>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                    <div class="card-header">
                            <div class="row">
                                <div class="col-sm-6">
                                    <h6 style="text-align: center;">REPORT PARAMETERS</h6>
                                </div>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control form-control-sm search" placeholder="Search..." value="<?php echo ($search) ? $search : ""; ?>">
                                </div>
                            </div>
                            <hr>
                            <div class="row">

                                <label for=""><b>FROM :</b></label>
                                <div class="col-sm-3">
                                    <input type="date" class="form-control form-control-sm from_date" value="<?php echo ($from_date) ? $from_date : ""; ?>">
                                </div>

                                <label for=""><b>TO :</b></label>
                              
                                <div class="col-sm-3">
                                    <input type="date" class="form-control form-control-sm to_date" value="<?php echo $to_date; ?>">
                                </div>

                                <div class="col-sm-2">
                                    <input type="hidden" class="analyst_id" value="<?php echo ($analyst_id) ? $analyst_id : ""; ?>">
                                    <input type="text" class="form-control form-control-sm analyst" placeholder="ANALYST" autocomplete="off" value="<?php echo ($analyst) ? $analyst : ""; ?>">
                                    <ul class="list-group-item analyst_list" style="display:none">
                                    </ul>
                                </div>

                             
                                <div class="col-sm-2">

                                    <button type="button" class="btn btn-sm btn-default" title="GENERATE REPORT" onclick="search_filter_record()">
                                        <img src="<?php echo base_url('assets/images/email_open.png') ?>" alt="generate">
                                    </button>
                                    <a type="button" class="btn btn-sm btn-default" title="RESET PARAMETERS" href="<?php echo base_url('Analyst_performance_report') ?>">
                                        <img src="<?php echo base_url('assets/images/arrow_refresh.png') ?>" alt="generate">
                                    </a>
                                    
                                </div>

                                <div class="col-sm-1">
                                <?php if(exist_val('Analyst_performance_report/excel_export_analyst',$this->session->userdata('permission'))){ ?>

                                    <a type="button" class="btn btn-sm btn-default" title="EXPORT REPORT" href="<?php echo base_url('Analyst_performance_report/excel_export_analyst')?>">
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
                                       
                                        if ($analyst != "") {
                                            $analyst = base64_encode($analyst);
                                        } else {
                                            $analyst = "NULL";
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

                                        <th scope="col"><a href="<?php echo base_url('Analyst_performance_report/index/'.$analyst.'/'.$from_date.'/'.$to_date.'/'.$search.'/'.base64_encode('analyst').'/'.$order)?>">ANALYST</a></th>

                                        <th scope="col"><a href="<?php echo base_url('Analyst_performance_report/index/'.$analyst.'/'.$from_date.'/'.$to_date.'/'.$search.'/'.base64_encode('assigned_tests').'/'.$order)?>">ASSIGNED TEST COUNT </a></th>

                                        <th scope="col"><a href="<?php echo base_url('Analyst_performance_report/index/'.$analyst.'/'.$from_date.'/'.$to_date.'/'.$search.'/'.base64_encode('completed_tests').'/'.$order)?>">COMPLETED TEST COUNT</a></th>

                                       
                                    </tr>
                                </thead>

                                <tbody>

                                    <?php $sn = $this->uri->segment('9') + 1;
                                    if ($analyst_performance_list) {
                            
                                        foreach ($analyst_performance_list as $key => $item) { ?>
                                            <tr>
                                                <td><?php echo $sn; ?></td>
                                                <td><?php echo $item->analyst ?></td>
                                                <td><?php echo $item->assigned_tests ?></td>
                                                <td><?php echo $item->completed_tests ?></td>
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
                                 <?php if ($analyst_performance_list && count($analyst_performance_list) > 0) { ?>
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
        url = '<?php echo base_url("Analyst_performance_report/index"); ?>';
        if($('.analyst').val()==""){
            $('.analyst_id').val("");
        }
        analyst = $('.analyst_id').val();

       

        from_date = $('.from_date').val();
        to_date = $('.to_date').val();
        search = $('.search').val();

        if (analyst != "") {
            url = url + '/' + btoa(analyst);
        } else {
            analyst = "";
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

        $('.from_date').on('change',function(){
            if($('.to_date').val()==""){

                $.notify('Please select end date!', 'error');
            }
        })
        // gc number autosuggetion
        getAutolist_analyst(
            'analyst_id',
            'analyst',
            'analyst_list',
            'analyst_li',
            '',
            'admin_fname',
            'uidnr_admin as id,concat(admin_fname," ",admin_lname) as name',
            'admin_profile'
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

        function getAutolist_analyst(hide_input, input, ul, li, where, like, select, table) {

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
                var _URL = "<?php echo base_url('Analyst_performance_report/get_auto_list_analyst') ?>";
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