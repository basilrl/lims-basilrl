<?php if($to_date==""){
        $to_date = date('Y-m-d');
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
                    <h1>LAB PERFORMANCE REPORT</h1>
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
                                    <input type="date" class="form-control form-control-sm to_date" value="<?php echo ($to_date) ? $to_date : date('Y-m-d', strtotime(date('now'))); ?>">
                                </div>

                                <div class="col-sm-2">
                                    <input type="hidden" class="lab_id" value="<?php echo ($lab_id) ? $lab_id : ""; ?>">
                                    <input type="text" class="form-control form-control-sm lab" placeholder="LAB" autocomplete="off" value="<?php echo ($lab) ? $lab : ""; ?>">
                                    <ul class="list-group-item lab_list" style="display:none">
                                    </ul>
                                </div>

                             
                                <div class="col-sm-2">

                                    <button type="button" class="btn btn-sm btn-default" title="GENERATE REPORT" onclick="search_filter_record()">
                                        <img src="<?php echo base_url('assets/images/email_open.png') ?>" alt="generate">
                                    </button>
                                    <a type="button" class="btn btn-sm btn-default" title="RESET PARAMETERS" href="<?php echo base_url('Lab_performance_report') ?>">
                                        <img src="<?php echo base_url('assets/images/arrow_refresh.png') ?>" alt="generate">
                                    </a>
                                    
                                </div>

                                <div class="col-sm-1">


                                <?php if(exist_val('Lab_performance_report/excel_export_lab',$this->session->userdata('permission'))){ ?>

                                    <a type="button" class="btn btn-sm btn-default" title="EXPORT REPORT" href="<?php echo base_url('Lab_performance_report/excel_export_lab')?>">
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
                                       
                                        if ($lab != "") {
                                            $lab = base64_encode($lab);
                                        } else {
                                            $lab = "NULL";
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

                                        <th scope="col"><a href="<?php echo base_url('Lab_performance_report/index/'.$lab.'/'.$from_date.'/'.$to_date.'/'.$search.'/'.base64_encode('lab').'/'.$order)?>">LAB</a></th>

                                        <th scope="col"><a href="<?php echo base_url('Lab_performance_report/index/'.$lab.'/'.$from_date.'/'.$to_date.'/'.$search.'/'.base64_encode('assigned_tests').'/'.$order)?>">ASSIGNED TEST COUNT</a></th>

                                        <th scope="col"><a href="<?php echo base_url('Lab_performance_report/index/'.$lab.'/'.$from_date.'/'.$to_date.'/'.$search.'/'.base64_encode('completed_tests').'/'.$order)?>">COMPLETED TEST COUNT</a></th>

                                       
                                    </tr>
                                </thead>

                                <tbody>

                                    <?php $sn = $this->uri->segment('9') + 1;
                                    if ($lab_performance_list) {
                            
                                        foreach ($lab_performance_list as $key => $item) { ?>
                                            <tr>
                                                <td><?php echo $sn; ?></td>
                                                <td><?php echo $item->lab ?></td>
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
                                 <?php if ($lab_performance_list && count($lab_performance_list) > 0) { ?>
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
        url = '<?php echo base_url("Lab_performance_report/index"); ?>';
        if($('.lab').val()==""){
            $('.lab_id').val("");
        }
        lab_id = $('.lab_id').val();

       

        from_date = $('.from_date').val();
        to_date = $('.to_date').val();
        search = $('.search').val();

        if (lab_id != "") {
            url = url + '/' + btoa(lab_id);
        } else {
            lab_id = "";
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


        // gc number autosuggetion
        getAutolist_lab(
            'lab_id',
            'lab',
            'lab_list',
            'lab_li',
            '',
            'lab_name',
            'lab_id as id,lab_name as name',
            'mst_labs'
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

        function getAutolist_lab(hide_input, input, ul, li, where, like, select, table) {

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
                var _URL = "<?php echo base_url('Lab_performance_report/get_auto_list_lab') ?>";
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