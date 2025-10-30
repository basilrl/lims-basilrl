<style>
    .customer_list {
        width: 90%;
    }
</style>

<div class="content-wrapper">
    <section class="content-header">
        <!-- container fluid start -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1>QUOTES</h1>
                </div>
            </div>

            <hr>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-sm-6">
                                    <a class="btn btn-sm btn-primary add" href="<?php echo base_url('quotes_form') ?>">Add New</a>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <input class="customer_id" type="hidden" value="<?php echo ($customer_id) ? $customer_id : '' ?>" name="customer_id">
                                    <input class="form-control form-control-sm  input-sm customer_name" value="<?php echo ($customer_name) ? $customer_name->customer_name : ''; ?>" autocomplete="off" name="customer_name" type="text" placeholder="Type Customer Name">
                                    <ul class="list-group-item customer_list" style="display:none">
                                    </ul>
                                </div>

                                <div class="col-sm-3">
                                    <select name="" value="<?php echo ($quote_status) ? $quote_status : ""; ?>" class="form-control form-control-sm quote_status">

                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <input value="<?php echo (($search != 'NULL') ? $search : ""); ?>" id="search" class="form-control form-control-sm" type="text" placeholder="Search" aria-label="Search">
                                </div>

                                <div class="col-sm-3">
                                    <button onclick="searchfilter();" type="button" class="btn btn-sm btn-primary">SEARCH</button>
                                    <a class="btn btn-sm btn-primary" href="<?php echo base_url('quotes'); ?>">CLEAR</a>
                                </div>
                            </div>
                        </div>
                        <!-- end card header -->

                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th scope="col"><a href="">S. NO.</a></th>
                                        <th scope="col"><a href="">CUSTOMER</a></th>
                                        <th scope="col"><a href="">QUOTE REF NO.</a></th>
                                        <th scope="col"><a href="">QOUTE DATE</a></th>
                                        <th scope="col"><a href="">QOUTE VALUE</a></th>
                                        <th scope="col"><a href="">QOUTE STATUS</a></th>
                                        <th scope="col"><a href="">CREATED BY</a></th>
                                        <th scope="col"><a href="">CREATED ON</a></th>
                                        <th scope="col"><a href="">ACTION</a></th>
                                    </tr>
                                </thead>

                                <tbody>

                                    <?php $sn = $this->uri->segment('7') + 1;
                                    if ($quotes_list) {
                                        //   $quotes_list = $quotes_list[0];
                                        foreach ($quotes_list as $key => $item) { ?>
                                            <tr>
                                                <td><?php echo $sn; ?></td>
                                                <td><?php echo $item->customer_name ?></td>
                                                <td><?php echo $item->reference_no ?></td>
                                                <td><?php echo $item->quote_date ?></td>
                                                <td><?php echo $item->quote_value ?></td>
                                                <td><?php echo $item->quote_status ?></td>
                                                <td><?php echo $item->created_by ?></td>
                                                <td><?php echo $item->created_on ?></td>
                                                <td>
                                                    <a href="<?php echo base_url('edit_quote').'/'.$item->quote_id?>"><img alt="update quote" title="Update Quote" src="<?php echo base_url('assets/images/edit.png')?>"></a>

                                                  <?php if($item->quote_status=="Draft"){?>
                                                        <a data-id="<?php echo $item->quote_id?>" style="cursor:pointer;" data-bs-toggle="modal" data-bs-target=".generate_quote_popup" class="gen_quotes_btn">
                                                        <img alt="generate quote" title="Generate Quote" src="<?php echo base_url('assets/images/Generate quote.png')?>"></a> 
                                                     <?php }?>

                                                     <?php if($item->quote_status=="Cps Approval Pending"){?>
                                                        <a data-id="<?php echo $item->quote_id?>" style="cursor:pointer;" data-bs-toggle="modal" data-bs-target=".revert_quote_popup" class="revert_quotes_btn">
                                                        <img alt="generate pdf" title="Generate PDF" src="<?php echo base_url('assets/images/revert.png')?>"></a> 
                                                     <?php }?>

                                                     <?php if($item->quote_status=="Awaiting Approval"){?>
                                                        <a data-id="<?php echo $item->quote_id?>" style="cursor:pointer;" data-bs-toggle="modal" data-bs-target=".generatepdf_quote_popup" class="pdf_quotes_btn" data-url="<?php echo base_url('Quotes/GeneratePDF_quotes/' . $item->quote_id) ?>">
                                                        <img alt="PDF" title="Quote PDF" src="<?php echo base_url('assets/images/downloadpdf.png')?>"></a> 
                                                     <?php }?>

                                                    
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
                <span><?php echo $links ?></span>
                <span><?php echo $result_count; ?></span>
            </div>
        </div>
        <!-- container fluid end -->
    </section>
</div>


<div class="modal fade bd-example-modal-sm generate_quote_popup" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Generate Quote</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="generate_quotes" action="javascript:void(0);">
            <div class="modal-body">
                     <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <input type="hidden" name="quote_id" class="quote_id" id="" value="">
                    <p>Are You Sure ?</p>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">NO</button>
                <button type="submit" class="btn btn-primary generate_quote">YES</button>
            </div>
            </form>
        </div>
    </div>
</div>




<div class="modal fade generatepdf_quote_popup" id="" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Quote Details</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid" style="height:60vh">
                    <iframe class="quote_pdf" src="" frameborder="0" height="100%" width="100%"></iframe>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

            </div>
        </div>
    </div>
</div>



<div class="modal fade bd-example-modal-sm revert_quote_popup" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Generate Quote</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="revert_quotes" action="javascript:void(0);">
            <div class="modal-body">
                     <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <input type="hidden" name="quote_id" class="quote_id" id="" value="">
                    <p>Are You Sure ?</p>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">NO</button>
                <button type="submit" class="btn btn-primary  genpdf_quotes">YES</button>
            </div>
            </form>
        </div>
    </div>
</div>


<script>
    function searchfilter() {

        var url = '<?php echo base_url("quotes"); ?>';

        var customer_id = $('.customer_id').val();
        var quote_status = $('.quote_status').val();
        var search = $('#search').val();

        if (customer_id != '') {
            url = url + '/' + customer_id;
        } else {
            url = url + '/NULL';
        }
        if (quote_status != '') {
            url = url + '/' + btoa(quote_status);
        } else {
            url = url + '/NULL';
        }
        if (search != '') {
            url = url + '/' + btoa(search);
        } else {
            url = url + '/NULL';
        }

        
        window.location.href = url;

    }
</script>

<script>
    $(document).ready(function() {



        // generate quotes

        $('#generate_quotes').submit(function(e) {
           
           e.preventDefault();
            $.ajax({
                url: "<?php echo base_url('Quotes/generate_quote')?>",
                method: "post",
                data: new FormData(this),
                contentType: false,
                processData: false,
                success: function(data) {
                    var msg = $.parseJSON(data);
                    if (msg.status > 0) {
                        $.notify(msg.msg, 'success');
                        $('.revert_quote_popup').modal("hide");
                        location.reload();
                       
                    } else {
                        $.notify(msg.msg, 'error');
                    }
                }
            })

            e.stopimmediatepropagation();
            return false;
        })


        $('.gen_quotes_btn').on('click',function(){
            var quote_id = $(this).attr("data-id");
            $('.quote_id').val(quote_id);
        })


// revert quote

        $('#revert_quotes').submit(function(e) {
           
           e.preventDefault();
            $.ajax({
                url: "<?php echo base_url('Quotes/awaiting')?>",
                method: "post",
                data: new FormData(this),
                contentType: false,
                processData: false,
                success: function(data) {
                    var msg = $.parseJSON(data);
                    if (msg.status > 0) {
                        $.notify(msg.msg, 'success');
                        $('.generate_quote_popup').modal("hide");
                        location.reload();
                       
                    } else {
                        $.notify(msg.msg, 'error');
                    }
                }
            })
            e.stopimmediatepropagation();
            return false;
        })


        $('.revert_quotes_btn').on('click',function(){
            var quote_id = $(this).attr("data-id");
            $('.quote_id').val(quote_id);
        })




        // pdf

        $('.pdf_quotes_btn').on('click',function(){
          var src = $(this).attr('data-url');
          $('.quote_pdf').attr('src',src);
      })

        // qoute status

        quotes_status();

        // get customer filter
        $('.customer_name').focus(function(e) {
            getAutolist('customer_id', 'customer_name', 'customer_list', 'customer_li', 'status="1"', 'customer_name', 'customer_id as id,customer_name as name', 'cust_customers');

        })

        // $('.quote_status').on('change',function(){

        // })

        function quotes_status() {
            var quotes_status = $('.quote_status');
            var selectOP = "<option value =''>Select Quotes Status</option>";
            var NoRecordOP = "<option value =''>No Record Found</option>";
            const _tokken = $('meta[name="_tokken"]').attr('value');
            $.ajax({
                url: "<?php echo base_url('Quotes/get_quotes_status') ?>",
                method: "post",
                data: {
                    _tokken: _tokken
                },
                success: function(data) {
                    var data = $.parseJSON(data);
                    quotes_status.html("");
                    quotes_status.append(selectOP);
                    if (data) {
                        $.each(data, (i, v) => {
                            var StatusOp = "<option value ='" + v + "'>" + v + "</option>";
                            quotes_status.append(StatusOp);
                        })
                    } else {
                        quotes_status.append(NoRecordOP);
                    }
                }
            })

            e.stopimmediatepropagation();
            return false;

        }
    })
</script>