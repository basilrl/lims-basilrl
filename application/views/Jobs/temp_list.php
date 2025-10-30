
<link rel="stylesheet" href="<?php echo base_url('assets/dist/css/temp_reg.css');?>">

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>TEMPORARY REGISTRATIONS</h1>
                </div>
            
            </div>
            <div class="row">
            <div class="col-md-1 text-center">
            <?php if(exist_val('Temp_reg/addtemp_page',$this->session->userdata('permission'))){ ?>
                <a class="btn btn-sm btn-primary" href="<?php echo base_url('Temp_reg/addtemp_page')?>">Add New</a>
                <?php }?>
            </div>

        
            <div class="col-sm-3">
                <input type="hidden" value="<?php echo ($customer)? $customer:''?>" name="customer_id" class="customer_id">
                <input autocomplete="off" class="form-control form-control-sm input-sm cust_name" name="cust_name" type="text" placeholder="BY CUSTOMER" value="<?php echo ($customer_name)?$customer_name->customer_name:''?>">
                <ul class="list-group-item drop_list">
                </ul>
            </div>

            <div class="col-sm-2">

                <input type="hidden" value="<?php echo ($buyer)? $buyer:''?>" name="buyer_id" class="buyer_id">
                <input autocomplete="off" class="form-control form-control-sm  input-sm buyer_name" name="buyer_name" type="text" placeholder="BY BUYER" value="<?php echo ($buyer_name)?$buyer_name->customer_name:''?>">
                <ul class="list-group-item buyer_list">
                </ul>
            </div>
     
            <div class="col-sm-4">
                <input value="<?php echo (($search !='NULL') ? $search : " "); ?>" id="search" class="form-control form-control-sm" type="text" placeholder="Search" aria-label="Search">
            </div>
            <div class="col-sm-2">
                <button onclick="searchfilter();" type="button" class="btn btn-sm btn-primary">SEARCH</button>
                <a class="btn btn-sm btn-primary" href="<?php echo base_url('Temp_reg/index'); ?>">CLEAR</a>
            </div>

        </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <section class="card">
    <div class="container-fluid">
        
        <div class="card-header" style="margin: 0 auto;">
        <div class="card-body" style="margin:0 auto;">
            <table id="emp_table" class="table table-sm">
                <thead>
                    <tr>
                        <?php $search=( ($search !='NULL' ) ? base64_encode($search) : 'NULL');?>
                        <?php $customer=( $customer)? $customer: 'NULL'?>
                        <?php $buyer=( $buyer)? $buyer: 'NULL'?>

                        <th scope="col"><a href="<?php echo base_url('Temp_reg/index'); ?>">SL NO.</a>
                        </th>

                        <th scope="col" style="<?php echo (($this->session->userdata('col_2')) && ($this->session->userdata('col_2') == 'true')) ? 'display:none':'';?> "><a href="<?php echo base_url('Temp_reg/index/'.$customer.'/'.$buyer.'/'.$search.'/temp_no'.'/'.$order); ?>">TEMPORARY NO.</a>
                        </th>

                        <th scope="col" style="<?php echo (($this->session->userdata('col_3')) && ($this->session->userdata('col_3') == 'true')) ? 'display:none':'';?> "><a href="<?php echo base_url('Temp_reg/index/'.$customer.'/'.$buyer.'/'.$search.'/cus.customer_name'.'/'.$order); ?>">CUSTOMER NAME</a>
                        </th>

                        <th scope="col" style="<?php echo (($this->session->userdata('col_4')) && ($this->session->userdata('col_4') == 'true')) ? 'display:none':'display:none';?> "><a href="<?php echo base_url('Temp_reg/index/'.$customer.'/'.$buyer.'/'.$search.'/tr.customer_email'.'/'.$order); ?>">CUSTOMER EMAIL</a>
                        </th>

                        <th scope="col" style="<?php echo (($this->session->userdata('col_5')) && ($this->session->userdata('col_5') == 'true')) ? 'display:none':'';?> "><a href="<?php echo base_url('Temp_reg/index/'.$customer.'/'.$buyer.'/'.$search.'/buyer'.'/'.$order); ?>">BUYER NAME</a>
                        </th>

                        <th scope="col" style="<?php echo (($this->session->userdata('col_6')) && ($this->session->userdata('col_6') == 'true')) ? 'display:none':'';?> "><a href="<?php echo base_url('Temp_reg/index/'.$customer.'/'.$buyer.'/'.$search.'/con.contact_name'.'/'.$order); ?>">CONTACT NAME</a>
                        </th>

                        <th scope="col" style="<?php echo (($this->session->userdata('col_7')) && ($this->session->userdata('col_7') == 'true')) ? 'display:none':'display:none';?> "><a href="<?php echo base_url('Temp_reg/index/'.$customer.'/'.$buyer.'/'.$search.'/con.email'.'/'.$order); ?>">CONTACT EMAIL</a>
                        </th>

                        <th scope="col" style="<?php echo (($this->session->userdata('col_8')) && ($this->session->userdata('col_8') == 'true')) ? 'display:none':'display:none';?> "><a href="<?php echo base_url('Temp_reg/index/'.$customer.'/'.$buyer.'/'.$search.'/no_of_samples'.'/'.$order); ?>">NO. OF SAMPLES</a>
                        </th>

                        <th scope="col" style="<?php echo (($this->session->userdata('col_9')) && ($this->session->userdata('col_9') == 'true')) ? 'display:none':'';?> "><a href="<?php echo base_url('Temp_reg/index/'.$customer.'/'.$buyer.'/'.$search.'/reference_no'.'/'.$order); ?>">REFERENCE NO.</a>
                        </th>

                        <th scope="col" style="<?php echo (($this->session->userdata('col_10')) && ($this->session->userdata('col_10') == 'true')) ? 'display:none':'display:none';?> "><a href="<?php echo base_url('Temp_reg/index/'.$customer.'/'.$buyer.'/'.$search.'/sample_receiving_date'.'/'.$order); ?>">SAMPLE RECEIVE DATE</a>
                        </th>

                        <th scope="col" style="<?php echo (($this->session->userdata('col_11')) && ($this->session->userdata('col_11') == 'true')) ? 'display:none':'display:none';?> "><a href="<?php echo base_url('Temp_reg/index/'.$customer.'/'.$buyer.'/'.$search.'/report_date'.'/'.$order); ?>">REPORT DATE</a>
                        </th>

                        <th scope="col" style="<?php echo (($this->session->userdata('col_12')) && ($this->session->userdata('col_12') == 'true')) ? 'display:none':'display:none';?> "><a href="<?php echo base_url('Temp_reg/index/'.$customer.'/'.$buyer.'/'.$search.'/service'.'/'.$order); ?>">SERVICE</a>
                        </th>

                        <th scope="col" style="<?php echo (($this->session->userdata('col_13')) && ($this->session->userdata('col_13') == 'true')) ? 'display:none':'display:none';?> "><a href="<?php echo base_url('Temp_reg/index/'.$customer.'/'.$buyer.'/'.$search.'/style_no'.'/'.$order); ?>">STYLE NO.</a>
                        </th>

                        <th scope="col" style="<?php echo (($this->session->userdata('col_14')) && ($this->session->userdata('col_14') == 'true')) ? 'display:none':'display:none';?> "><a href="<?php echo base_url('Temp_reg/index/'.$customer.'/'.$buyer.'/'.$search.'/po_no'.'/'.$order); ?>">P.O NO.</a>
                        </th>

                        <th scope="col" style="<?php echo (($this->session->userdata('col_15')) && ($this->session->userdata('col_15') == 'true')) ? 'display:none':'display:none';?> "><a href="<?php echo base_url('Temp_reg/index/'.$customer.'/'.$buyer.'/'.$search.'/colour'.'/'.$order); ?>">COLOUR</a>
                        </th>

                        <th scope="col" style="<?php echo (($this->session->userdata('col_16')) && ($this->session->userdata('col_16') == 'true')) ? 'display:none':'display:none';?> "><a href="<?php echo base_url('Temp_reg/index/'.$customer.'/'.$buyer.'/'.$search.'/c_origin'.'/'.$order); ?>">COUNTRY OF ORIGIN</a>
                        </th>

                        <th scope="col" style="<?php echo (($this->session->userdata('col_17')) && ($this->session->userdata('col_17') == 'true')) ? 'display:none':'display:none';?> "><a href="<?php echo base_url('Temp_reg/index/'.$customer.'/'.$buyer.'/'.$search.'/d_origin'.'/'.$order); ?>">COUNTRY OF DESTINATION</a>
                        </th>

                        <th scope="col" style="<?php echo (($this->session->userdata('col_18')) && ($this->session->userdata('col_18') == 'true')) ? 'display:none':'display:none';?> "><a href="<?php echo base_url('Temp_reg/index/'.$customer.'/'.$buyer.'/'.$search.'/created_by'.'/'.$order); ?>">CREATED BY</a>
                        </th>

                        <th scope="col" style="<?php echo (($this->session->userdata('col_19')) && ($this->session->userdata('col_19') == 'true')) ? 'display:none':'display:none';?> "><a href="<?php echo base_url('Temp_reg/index/'.$customer.'/'.$buyer.'/'.$search.'/user_name'.'/'.$order); ?>">CRM USER</a>
                        </th>

                        <th scope="col" style="<?php echo (($this->session->userdata('col_20')) && ($this->session->userdata('col_20') == 'true')) ? 'display:none':'display:none';?> "><a href="<?php echo base_url('Temp_reg/index/'.$customer.'/'.$buyer.'/'.$search.'/trf_gen_status'.'/'.$order); ?>">TRF STATUS</a>
                        </th>

                        <th scope="col" style="<?php echo (($this->session->userdata('col_21')) && ($this->session->userdata('col_21') == 'true')) ? 'display:none':'';?> "><a href="<?php echo base_url('Temp_reg/index/'.$customer.'/'.$buyer.'/'.$search.'/created_on'.'/'.$order); ?>">CREATED ON</a>
                        </th>

                        <th scope="col" style="<?php echo (($this->session->userdata('col_22')) && ($this->session->userdata('col_22') == 'true')) ? 'display:none':'display:none';?> "><a href="<?php echo base_url('Temp_reg/index/'.$customer.'/'.$buyer.'/'.$search.'/status'.'/'.$order); ?>">STATUS</a>
                        </th>

                        <th scope="col"><a href="<?php echo base_url('Temp_reg/index'); ?>">ACTION</a>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php (empty($this->uri->segment(8)))? $sn = 1:$sn= $this->uri->segment(8) + 1;?>
                    <?php if($temp_list):?>
                    <?php foreach($temp_list as $item):?>

                    <tr>
                        <th>
                            <?=$sn;?>
                        </th>
                        <td style="<?php  echo (($this->session->userdata('col_2')) && ($this->session->userdata('col_2') == 'true')) ? 'display:none':'';?>">
                            <?=$item->temp_no?></td>

                        <td style="<?php echo (($this->session->userdata('col_3')) && ($this->session->userdata('col_3') == 'true')) ? 'display:none':'';?> ">
                            <?=$item->customer_name?></td>

                        <td style="<?php echo (($this->session->userdata('col_4')) && ($this->session->userdata('col_4') == 'true')) ? 'display:none':'display:none';?> ">
                            <?=$item->customer_email?></td>

                        <td style="<?php echo (($this->session->userdata('col_5')) && ($this->session->userdata('col_5') == 'true')) ? 'display:none':'';?> ">
                            <?=$item->buyer?></td>

                        <td style="<?php echo (($this->session->userdata('col_6')) && ($this->session->userdata('col_6') == 'true')) ? 'display:none':'';?> ">
                            <?=$item->contact_name?></td>

                        <td style="<?php echo (($this->session->userdata('col_7')) && ($this->session->userdata('col_7') == 'true')) ? 'display:none':'display:none';?> ">
                            <?=$item->email?></td>

                        <td style="<?php echo (($this->session->userdata('col_8')) && ($this->session->userdata('col_8') == 'true')) ? 'display:none':'display:none';?> ">
                            <?=$item->no_of_samples?></td>

                        <td style="<?php echo (($this->session->userdata('col_9')) && ($this->session->userdata('col_9') == 'true')) ? 'display:none':'';?> ">
                            <?=$item->reference_no?></td>

                        <td style="<?php echo (($this->session->userdata('col_10')) && ($this->session->userdata('col_10') == 'true')) ? 'display:none':'display:none';?> ">
                            <?=$item->sample_receiving_date?></td>

                        <td style="<?php echo (($this->session->userdata('col_11')) && ($this->session->userdata('col_11') == 'true')) ? 'display:none':'display:none';?> ">
                            <?=$item->report_date?></td>

                        <td style="<?php echo (($this->session->userdata('col_12')) && ($this->session->userdata('col_12') == 'true')) ? 'display:none':'display:none';?> ">
                            <?=$item->service?></td>

                        <td style="<?php echo (($this->session->userdata('col_13')) && ($this->session->userdata('col_13') == 'true')) ? 'display:none':'display:none';?> ">
                            <?=$item->style_no?></td>

                        <td style="<?php echo (($this->session->userdata('col_14')) && ($this->session->userdata('col_14') == 'true')) ? 'display:none':'display:none';?> ">
                            <?=$item->po_no?></td>

                        <td style="<?php echo (($this->session->userdata('col_15')) && ($this->session->userdata('col_15') == 'true')) ? 'display:none':'display:none';?> ">
                            <?=$item->colour?></td>

                        <td style="<?php echo (($this->session->userdata('col_16')) && ($this->session->userdata('col_16') == 'true')) ? 'display:none':'display:none';?> ">
                            <?=$item->c_origin?></td>

                        <td style="<?php echo (($this->session->userdata('col_17')) && ($this->session->userdata('col_17') == 'true')) ? 'display:none':'display:none';?> ">
                            <?=$item->d_origin?></td>

                        <td style="<?php echo (($this->session->userdata('col_18')) && ($this->session->userdata('col_18') == 'true')) ? 'display:none':'display:none';?> ">
                            <?=$item->user_name?></td>

                        <td style="<?php echo (($this->session->userdata('col_19')) && ($this->session->userdata('col_19') == 'true')) ? 'display:none':'display:none';?> ">
                            <?=$item->user_name?></td>

                        <td style="<?php echo (($this->session->userdata('col_20')) && ($this->session->userdata('col_20') == 'true')) ? 'display:none':'display:none';?> ">
                            <?=($item->trf_gen_status=='0')?'NOT-GENERATED':'GENERATED'?></td>

                        <td style="<?php echo (($this->session->userdata('col_21')) && ($this->session->userdata('col_21') == 'true')) ? 'display:none':'';?> ">
                            <?=$item->created_on?></td>

                        <td style="<?php echo (($this->session->userdata('col_22')) && ($this->session->userdata('col_22') == 'true')) ? 'display:none':'display:none';?> ">
                            <?=($item->status=='1') ? 'ACTIVE':'IN-ACTIVE'?></td>

                        <td>
                        <?php if(exist_val('Temp_reg/edit_temp',$this->session->userdata('permission'))){ ?>
                            <a href=" <?php echo base_url('Temp_reg/edit_temp/'.$item->temp_reg_id)?>" class="btn btn-sm btn-default" title="Edit"><img src="<?php echo base_url('assets/images/icon/edit.png'); ?>" class="edit" alt="Edit"></a>
                            <?php }?>

                            <?php if(exist_val('Temp_reg/send_temp',$this->session->userdata('permission'))){ ?>
                            <a href="<?php echo base_url('Temp_reg/send_temp/'.$item->temp_reg_id)?>" class="btn btn-sm btn-default" title="Send Email"><img src="<?php echo base_url('assets/images/email.png'); ?>" class="edit" alt="Email"></a>

                            <?php }?>

                            <?php if(exist_val('Temp_reg/open_worksheet',$this->session->userdata('permission'))){ ?>
                            <a class="click_worksheet btn btn-default btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModalCenter" data-url="<?php echo base_url('Temp_reg/open_worksheet/'.$item->temp_reg_id)?>" class="btn btn-sm btn-default" title="Worksheet"><img src="<?php echo base_url('assets/images/worksheet.png'); ?>" class="edit" alt="Worksheet"></a>
                            <?php }?>

                        </td>
                    </tr>
                    <?php $sn++; endforeach;?>
                    <?php endif;?>
                    <?php if($temp_list==NULL):?>
                    <tr>
                        <td>NO RECORD FOUND</td>
                    </tr>
                    <?php endif;?>
                </tbody>
            </table>



        </div>
        <div class="card-header">
            <span id=""><?php echo $links ?></span>
            <span id=""><?php echo $result_count; ?></span>
        </div>
        </div>
        </div>

    </div>




    <!-- <div class="row">
        <div class="col-md-6">
            <?php echo $links ?>
        </div>
        <div class="col-md-6">
            <?php echo $result_count; ?>
        </div>
        </div>
        /.content
    </div> -->


    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Worksheet</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid" style="height:60vh">
                        <iframe class="worksheet" src="" frameborder="0" height="100%" width="100%"></iframe>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                </div>
            </div>
        </div>
    </div>



</section>

<script>
    function searchfilter() {
        // var $ = document.querySelector;
        var url = "<?php echo base_url(); ?>Temp_reg/index";

        // var temp_number = $('.temp_number').val();
        var cust_name = $('.customer_id').val();
        var buy_name = $('.buyer_id').val();
        // var ref = $('.ref').val();
        var search = $('#search').val();

        // if (temp_number != '') {
        //     url = url + '/' +btoa(temp_number);
        // } else {
        //     url = url + '/NULL';
        // }
        if (cust_name != '') {
            url = url + '/' + cust_name;
        } else {
            url = url + '/NULL';
        }
        if (buy_name != '') {
            url = url + '/' + buy_name;
        } else {
            url = url + '/NULL';
        }
        // if(ref!=''){
        //     url = url+'/'+ref;
        // }
        // else{
        //     url = url + '/NULL';
        // }
        if (search != '') {
            url = url + '/' + btoa(search);
        } else {
            url = url + '/NULL';
        }

        window.location.href = url;
        // console.log(url);
    }
</script>
