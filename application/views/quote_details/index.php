
  <?php
          $division = ($this->uri->segment('3') != 'NULL') ? base64_decode($this->uri->segment('3')) : '';
          $status = ($this->uri->segment('4') != 'NULL') ? base64_decode($this->uri->segment('4')) : '';
          $created_by = ($this->uri->segment('5') != 'NULL') ? base64_decode($this->uri->segment('5')) : '';
          $start_date = ($this->uri->segment('6') != 'NULL') ? base64_decode($this->uri->segment('6')) : '';
          $end_date = ($this->uri->segment('7') != 'NULL') ? base64_decode($this->uri->segment('7')) : '';
          
  ?>


<div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Quote Contact Details</h1>
          </div>
          </div>



            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
            <div class="col-md-1 mt-3" style="padding:10px">
                <h3 class="card-tools"><Button class="btn btn-primary "><a style="color:white;" href="<?php echo base_url(); ?>Quote_details/add">Add</a></Button></h3>
            </div>


            <div style="width:100%; height:150px; background-color:white;">
            <div class="row">
            &nbsp;&nbsp;&nbsp;&nbsp;<div class="col-md-4" >
                    <label>Created By:</label>
                    <select name="created_by" id="created_by" class="form-control ">
                    
                      <option value="<?php echo $created_by; ?>">Select Created By</option>
                      <?php foreach ($created_by_name as $cr_name) { ?>
                        <option  value="<?php echo $cr_name->uidnr_admin; ?>"> <?php echo $cr_name->created_by; ?> </option>
                      <?php } ?>
                        </select>
                       </div>


                       <div class="col-md-4" >
                       <label>Division:</label>
                      <select name="division" id="division" class="form-control ">
                      <option disabled selected>Select Division...</option>
                 
                      <?php if (isset($division_name)) {
                     foreach ($division_name as $de) { ?>
                         <option  value="<?php echo $de->division_id; ?>" > <?php echo $de->division; ?> </option>
                      <?php } }?>
                     
                    </select>
                  </div>
                  <div class="col-md-3" style="position: relative; top:22px;">
                  <select name="status" id="status" class="form-control">
                          <option  disabled selected>select</option>
                        
                          
                          <option value="active" >Active</option>
                          <option value="inactive" >Inactive</option>
                      </select>
                  </div>
                  


            </div>
            <div class="row">

            <div class="col-md-1"></div>
              <div class="col-md-4">
                    <div class="form-group">
                     <label>Start Date:</label>
                    <input type="date" name="start_date" id="start_date" value="<?php echo $start_date?>"  class="form-control form-control-sm " placeholder="Start Date..."  style="height:38px">
                    </div>
              </div> 

      <div class="col-md-4" >
          <div class="form-group">
              <label>End Date:</label>
               <input type="date" name="end_date" id="end_date" value="<?php echo $end_date?>"  class="form-control form-control-sm " placeholder="End Date..."  style="height:38px">
          </div>
      </div> 
              
            <div class="col-md-2 " style="position: relative; top:25px;" >
              <button  style="width:40%;"type="button" class="btn btn-primary" id="search">Search</button>
              <button style="width:40%;"type="button" class="btn btn-danger  " onclick="location.href='<?php echo base_url('Quote_details/index'); ?>'">Clear</button>
          </div>

            </div>
            </div>


            
            <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <div class="">
                  <div class="row">
                 
                  </div>
                  </div>
                </div>
              </div>
              <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap table-sm table-striped table-bordered">
                  <thead>
                  <tr class="text-primary">
                    <?php
                      if ($division) {
                        $division = base64_encode($division);
                      } else {
                        $division = "NULL";
                      }
                      if ($status) {
                        $status = base64_encode($status);
                      } else {
                        $status = "NULL";
                      }
                      if ($created_by) {
                        $created_by = base64_encode($created_by);
                      } else {
                        $created_by = "NULL";
                      }
                      if ($start_date) {
                        $start_date = base64_encode($start_date);
                      } else {
                        $start_date = "NULL";
                      }
                      if ($end_date) {
                        $end_date = base64_encode($end_date);
                      } else {
                        $end_date = "NULL";
                      }
                      if ($order!=NULL) {
                        $order = base64_encode($order);
                      } else {
                        $order = "NULL";
                      }
                      ?>
                      <th style="color:black;">S. No.</th>
                      <th><a href="<?php echo base_url('Quote_details/index/' . $division . '/' . $status . '/' . $created_by . '/' . $start_date . '/' . $end_date . '/' . $order . '/' . 'division') ?>">Division</a></th>
                      <th><a href="<?php echo base_url('Quote_details/index/' . $division . '/' . $status . '/' . $created_by . '/' . $start_date . '/' . $end_date . '/' . $order . '/' . 'status') ?>">Status</a></th>
                      <th><a href="<?php echo base_url('Quote_details/index/' . $division . '/' . $status . '/' . $created_by . '/' . $start_date . '/' . $end_date . '/' . $order . '/' . 'created_on') ?>">Created On</a></th>
                      <th><a href="<?php echo base_url('Quote_details/index/' . $division . '/' . $status . '/' . $created_by . '/' . $start_date . '/' . $end_date . '/' . $order . '/' . 'created_by') ?>">Created By</a></th>
                      <th style="color:black;">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php 
                  if(!empty($Quote_contact_details)) 
                {
                      $seg=$this->uri->segment(10); 
                    if(is_numeric($seg)){
                      $sno = $this->uri->segment(10) + 1;
                    }else{
                      $sno = 1;                    
                    }   

                    // Showing data from data base to table using foreach by kamal on 14th june 2022

                       foreach($Quote_contact_details as $value)
                    { 
                        ?>
                    <tr>
                        <td><?php echo $sno++;?></td>
                        <td><?php echo $value['division'];?></td>
                        <td><?php echo $value['status'];?></td>
                        
                        <!-- <td><?php echo $value['status'];?></td> -->
                        <td><?php echo $value['created_on'];?></td>
                        <td><?php echo $value['created_by'];?></td>
                        <td>
                        
                        <!-- Edit button for editing of data in mst category on 6th of june 2022 by kamal -->

                         <?php if (1) { ?>
                              <a href="<?php echo base_url(); ?>Quote_details/edit/<?php echo $value['details_id']; ?>" class="btn btn-sm"><img src="<?php echo base_url('assets/images/mem_edit.png'); ?>" title="Edit" class="edit" alt="Edit" width="20px"></a>
                            <?php } ?>
                        </td>
                    </tr>
                    <?php }
                    ?>
                    <?php } ?>
                  </tbody>
                </table>

              </div>

              <!-- Pagination of mst category by kamal on 6th june 2022 -->

             
                 <div class="card-header">
                <?php if ($Quote_contact_details && count($Quote_contact_details  ) > 0) { ?>
                  <span id="pagination"><?php echo ($links) ? $links : ''; ?></span>
                  <span><?php echo ($result_count)?$result_count:''; ?></span>
                  <span><?php  ?></span>
                  <?php } else { ?>
                     <h3>NO RECORD FOUND</h3> 
                    <?php } ?>
          
        <?php  if(count($Quote_contact_details  ) <1) { ?>
         <h3></h3>
        <?php } ?>
        </div>
        </div>

        </div>
</div>
<script>

//  here we are using javascipt for searching filter on mst category by kamal on 6th of june 2022

      // var main_url;
      $(document).ready(function() {
const url = $('body').data('url');
        // alert("kamal");
$(document).on('click', '#search', function() {
  filter();
});
function filter()
{
  // alert("kamal");
  var div = $('#division').val(); 
  var status = $('#status').val();
  var created_by = $('#created_by').val();
  var start_date = $('#start_date').val();
  var end_date = $('#end_date').val();
  // alert(start_date);
  // alert(end_date);
  // alert($this->uri->segment(7));
  if (div == '') {
    div = 'NULL';
  } else {
    div = btoa(div); 
  }
  if (created_by=='') {
    created_by = 'NULL';
  } else {
     created_by=btoa(created_by);
  }

  if (status == '') {
    status = 'NULL';
  } else {
    status = btoa(status);
  }

  if (start_date == '') {
    start_date = 'NULL';
  } else {
    start_date = btoa(start_date);
  }
  if (end_date == '') {
    end_date = 'NULL';
  } else {
    end_date = btoa(end_date);
  }
  // main_url=
  if(start_date=='NULL'&&end_date!='NULL')
  {
    alert("Pleasure Select Start Date");
  }
   else if(start_date!='NULL'&&end_date=='NULL')
  {
    alert("Pleasure Select End Date");
  }
  // Setting the link in url according to searching filter on mst category on 6th of june 2022

  else{
  window.location.replace(url + 'Quote_details/index/' + div + '/' + status+ '/' + created_by
  +'/'+ start_date+'/'+end_date);
  }
}



$(document).on('click', '#reset', function() {
  $('#division').val('');
  $('#status').val('');
  $('#created_by').val('');
  $('#start_date').val('');
  $('#end_date').val('');
  filter(0)
});
});

  </script>

   </body>
