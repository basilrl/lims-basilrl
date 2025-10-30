<script>
    function inArray(value, arraylist) {
        var length = arraylist.length;
        for(var i = 0; i < length; i++) 
        {
            if(arraylist[i] == value) 
            return true;
        }
        return false;
    }
    $(document).ready(function () {
        $.validator.setDefaults({
            submitHandler: function () {
                alert("submitted!");
            }
        });

        $("body").on("click", ".accordion_head", function () {
            if ($('.accordion_body').is(':visible')) {
                $(".accordion_body").slideUp(300);
                $(".plusminus").text('+');
            }
            if ($(this).next(".accordion_body").is(':visible')) {
                $(this).next(".accordion_body").slideUp(300);
                $(this).children(".plusminus").text('+');
            } else {
                $(this).next(".accordion_body").slideDown(300);
                $(this).children(".plusminus").text('-');
            }
        });
    });
</script>

<section class="adjustheight">
<main class="main" >
<div class="container-fluid">
    <div class="container text-center"><br/>
        <h2 class="text-info"><i class="fa fa-tasks"></i> Communication</h2>
    </div> <hr>
    <div class="container-fluid mt-4">
        <div class="row">
            <?php if($type_customer) {
                $customer_type = $type_customer;
            } else { 
                $customer_type = "";
            } ?>
            <div class="col-sm-2">
                <select name="customer_type" id="customer_type" class="form-control form-control-sm">
                    <option value="">Select Customer Type</option>
                        <?php foreach ($cust_type as $type) { ?>
                            <option value="<?php echo $type->customer_type; ?>" <?php echo ($customer_type == $type->customer_type) ? "selected" : ""; ?> > <?php echo $type->customer_type; ?> </option>
                        <?php } ?>
                </select>
            </div>
            <?php if($name_customer) {
                $customer_id = $name_customer;
            } else {
                $customer_id = 0;
            } ?>
            <div class="col-sm-2">
                <select name="customer_id" id="customer_id" class="form-control form-control-sm">
                    <option value="">Select Customer Name</option>
                        <?php foreach ($cust_name as $name) { ?>
                            <option value="<?php echo $name->customer_id; ?>" <?php echo ($customer_id == $name->customer_id) ? "selected" : "" ; ?> > <?php echo $name->customer_name; ?> </option>
                        <?php } ?>
                </select>
            </div>
            <?php if($name_contact) { 
                $contact_id = $name_contact;
            } else {
                $contact_id = 0;
            } ?>
            <div class="col-sm-2">
                <select name="contact_name" id="contact_name" class="form-control form-control-sm">
                    <option value="">Select Contact Name</option>
                        <?php foreach ($contact_name as $cname) { ?>
                            <option value="<?php echo $cname->contact_id; ?>" <?php echo ($contact_id == $cname->contact_id) ? "selected" : ""; ?> > <?php echo $cname->contact_name; ?> </option>
                        <?php } ?>
                </select>
            </div>
            <?php if($connect_to) {
                $connected_to = $connect_to;
            } else{
                $connect_to = "";
            } ?>
            <div class="col-sm-2">
                <select name="connected_to" id="connected_to" class="form-control form-control-sm">
                    <option value="">Select Connected To</option>
                        <?php foreach ($connected_to_comm as $connect) { ?>
                            <option value="<?php echo $connect->connected_to; ?>" <?php echo ($connected_to == $connect->connected_to) ? "selected" : ""; ?> > <?php echo $connect->connected_to; ?> </option>
                        <?php } ?>
                </select>
            </div>
            <?php if($created_by) {
                $uidnr_admin = $created_by;
            } else{
                $uidnr_admin = "";
            } ?>
            <div class="col-sm-2">
                <select name="created_by" id="created_by" class="form-control form-control-sm">
                    <option value="">Select Created By</option>
                        <?php foreach ($created_by_name as $cr_name) { ?>
                            <option value="<?php echo $cr_name->uidnr_admin; ?>" <?php echo ($uidnr_admin == $cr_name->uidnr_admin) ? "selected" : ""; ?> > <?php echo $cr_name->created_by; ?> </option>
                        <?php } ?>
                </select>
            </div>
            <div class="col-sm-2">
                <form class="form-inline" action="<?= base_url('communication/') ?>" method="POST">
                    <input type="hidden" class="txt_csrfname" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>"> 
                    <input name="search" class="form-control form-control-sm search_field" type="text" placeholder="Search" aria-label="Search">
                </form>
            </div>
        </div>
        <div class="text-right mt-4">
            <button onclick="search()" class="btn btn-primary ml-3 btn-sm"> <i class="fa fa-search" aria-hidden="true"></i> Search </button>
            <a href="<?php echo base_url('communication') ?>" class="btn btn-sm btn-success ml-3"> <i class="fa fa-eraser"></i> Clear </a>
        </div>
    </div> <br>
    <!-- table for listing  -->
    <div class="table-responsive table-sm">
        <table id="roleTable" class="display table" width="100%" > 
            <thead>  
                <tr> 
                    <th scope="col">SN</th>  
                    <th scope="col"><a href="<?php echo base_url('communication' .'/' .$type_customer. '/' .$name_customer. '/'. 
                    $name_contact. '/' .(($connect_to)?$connect_to:'NULL'). '/' .$created_by. '/' .$search. '/' .'cust.customer_type'. '/'. $order) ?>">Customer Type</a></th>  
                    <th scope="col"><a href="<?php echo base_url('communication' .'/' .$type_customer. '/' .$name_customer. '/'. 
                    $name_contact. '/' .(($connect_to)?$connect_to:'NULL'). '/' .$created_by. '/' .$search. '/' .'cust.customer_name'. '/'. $order) ?>">Customer </a></th>  
                    <th scope="col"><a href="<?php echo base_url('contact' .'/' .$type_customer. '/' .$name_customer. '/'. 
                    $name_contact. '/' .(($connect_to)?$connect_to:'NULL'). '/' .$created_by. '/' .$search. '/' .'contact.contact_name'. '/'. $order) ?>">Contact </a></th>   
                    <th scope="col">Subject</th> 
                    <th scope="col">Date of Comm</th>  
                    <th scope="col"><a href="<?php echo base_url('communication' .'/' .$type_customer. '/' .$name_customer. '/'. 
                    $name_contact. '/' .(($connect_to)?$connect_to:'NULL'). '/' .$created_by. '/' .$search. '/' .'comm.communication_mode'. '/'. $order) ?>">Comm Mode</a></th>  
                    <th scope="col"><a href="<?php echo base_url('communication' .'/' .$type_customer. '/' .$name_customer. '/'. 
                    $name_contact. '/' .(($connect_to)?$connect_to:'NULL'). '/' .$created_by. '/' .$search. '/' .'comm.medium'. '/'. $order) ?>">Comm Medium</a></th>  
                    <th scope="col"><a href="<?php echo base_url('communication' .'/' .$type_customer. '/' .$name_customer. '/'. 
                    $name_contact. '/' .(($connect_to)?$connect_to:'NULL'). '/' .$created_by. '/' .$search. '/' .'comm.connected_to'. '/'. $order) ?>">Connected To</a></th>  
                    <th scope="col">Created By</th>
                    <th scope="col">Created On</th>
                </tr>  
            </thead>  
            <tbody>
                <?php
                if ($result) {
                    if (empty($this->uri->segment(10)))
                        $i = 1;
                    else
                        $i = $this->uri->segment(10) + 1;
                    foreach ($result as $row) {
                ?>  
                <tr>  
                    <td><?php echo $i; ?></td>  
                    <td><?php echo $row->customer_type; ?></td> 
                    <td><?php echo $row->customer_name; ?></td> 
                    <td><?php echo $row->contact_name; ?></td> 
                    <td><?php echo $row->subject; ?></td> 
                    <td><?php echo $row->date_of_communication; ?></td> 
                    <td><?php echo $row->communication_mode; ?></td> 
                    <td><?php echo $row->medium; ?></td>  
                    <td><?php echo $row->connected_to; ?></td> 
                    <td><?php echo $row->created_by; ?></td>   
                    <td><?php echo $row->created_on; ?></td>    
                    <?php $i++; } } ?>
                </tr>  
            </tbody>  
        </table>  
    </div>
</div>
<div class="container-fluid">
    <div class="text-left">
        <?php echo $links ?>
        <?php if($result && count($result) > 0) { ?>
            <?php echo "<span class='text-dark font-weight-bold'>" . $result_count . "</span>"; ?>
        <?php } else{ ?>
            <?php echo "<h5 class='text-center font-weight-bold'> NO RECORD FOUND  </h5>"; ?>
        <?php } ?>
    </div>
</div>
</main>
</section>

<script type="text/javascript">
    function search()
    {
        var base_url = "<?php echo base_url('communication'); ?>"
        var customer_type = $('#customer_type').val();
        var customer_id = $('#customer_id').val();
        var contact_name = $('#contact_name').val();
        var connect_to = $('#connected_to').val();
        var created_by = $('#created_by').val();
        var search = $('.search_field').val();
        if (customer_type) {
          base_url =  base_url+'/'+btoa(customer_type);
        }else{
           base_url =  base_url+'/'+'NULL';
        }
        if (customer_id) {
           base_url = base_url+'/'+btoa(customer_id);
        }else{
           base_url =  base_url+'/'+'NULL';
        }
        if (contact_name) {
            base_url = base_url+'/'+btoa(contact_name);
        }else{
            base_url = base_url+'/'+'NULL';
        }
        if (connect_to) {
          base_url =  base_url+'/'+btoa(connect_to);
        }else{
           base_url =  base_url+'/'+'NULL';
        }
        if (created_by) {
          base_url =  base_url+'/'+btoa(created_by);
        }else{
           base_url =  base_url+'/'+'NULL';
        }
        if (search) {
          base_url =  base_url+'/'+btoa(search);
        }else{
           base_url =  base_url+'/'+'NULL';
        }
        location.href = base_url;
    }
</script>