      <!-- Content Wrapper. Contains page content -->
       <!-- Created by kamal Singh on 6th of June 2022  -->
       <!-- MST CATEGORY TABLE LISTING FILE -->



      <?php
          $category_name = ($this->uri->segment('3') != 'NULL') ? base64_decode($this->uri->segment('3')) : '';
          $category_code = ($this->uri->segment('4') != 'NULL') ? base64_decode($this->uri->segment('4')) : '';
          $created_by = ($this->uri->segment('5') != 'NULL') ? base64_decode($this->uri->segment('5')) : '';
          $start_date = ($this->uri->segment('6') != 'NULL') ? base64_decode($this->uri->segment('6')) : '';
          $end_date = ($this->uri->segment('7') != 'NULL') ? base64_decode($this->uri->segment('7')) : '';
          
  ?>
  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Category</h1>
          </div>
          </div>
        <br>
        <center> <h2 style="color:green;"><?php echo $this->session->flashdata('message_name'); ?></h2> </center>

        <div class="row" style="background-color:white;">

          <!-- Token to security on mst category by kamal on 6th of june 2022 -->

            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
            <div class="col-md-1 mt-3" style="padding:10px">
                <h3 class="card-tools"><Button class="btn btn-primary "><a style="color:white;" href="<?php echo base_url(); ?>Mst_category/add">Add</a></Button></h3>
            </div>

          <!-- SEARCH FILTER BY KAMAL of mst category table on 6th of june 2022 -->
         
        <div class="col-md-4">
          <div class="form-group">
              <label>Category Name:</label>
              <input type="text" name="category_name" id="category_name" value="<?php echo $category_name; ?>" class="form-control form-control-sm"  placeholder="Category Name" style="height:38px">
          </div>
        </div>

        <div class="col-md-4">
          <div class="form-group">
            <label>Category Code:</label>
            <input type="text" name="category_code" id="category_code" value="<?php echo $category_code; ?>" class="form-control form-control-sm"  placeholder="Category Code..." style="height:38px">
         </div>
        </div>

              <div class="col-md-3 mt-1" >
              <label>Created By:</label>
                    <select name="created_by" id="created_by" class="form-control ">
                    
                      <option value="<?php echo $created_by; ?>">Select Created By</option>
                      <?php foreach ($created_by_name as $cr_name) { ?>
                        <option  value="<?php echo $cr_name->uidnr_admin; ?>"> <?php echo $cr_name->created_by; ?> </option>
                      <?php } ?>
                    </select>
                  </div>
                      </div>
                      <div class="row" style="background-color:white;">
                      <div class="col-md-1"></div>
              <div class="col-md-5">
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
      

          <div class="col-md-2 mt-4" >
              <button  style="width:40%;"type="button" class="btn btn-primary" id="search">Search</button>
              <button style="width:40%;"type="button" class="btn btn-danger  " onclick="location.href='<?php echo base_url('Mst_category/index'); ?>'">Clear</button>
          </div>
      </div>
    </div>
    
    
           
        
    <!-- Main content -->

    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <div class="card-tools">
                  
                  </div>
                </div>
              </div>

              <!-- /.card-header -->

              <input type="hidden" id="order" value="">
              <input type="hidden" id="column" value="">
              <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap table-sm table-striped table-bordered">
                  <thead>
                    <tr class="text-primary">
                    <?php
                      if ($category_name) {
                        $category_name = base64_encode($category_name);
                      } else {
                        $category_name = "NULL";
                      }
                      if ($category_code) {
                        $category_code = base64_encode($category_code);
                      } else {
                        $category_code = "NULL";
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
                      if ($order!="") {
                        $order = base64_encode($order);
                      } else {
                        $order = "NULL";
                      }
                      ?>
                      <th style="color:black;">S. No.</th>
                      <th ><a href="<?php echo base_url('Mst_category/index/' . $category_name . '/' . $category_code . '/' . $created_by . '/' . $start_date . '/' . $end_date . '/' . $order . '/' . 'category_name') ?>">Category Name</a></th>
                      <!-- <th>Category Name</th> -->
                      <th ><a href="<?php echo base_url('Mst_category/index/' . $category_name . '/' . $category_code . '/' . $created_by . '/' . $start_date . '/' . $end_date . '/' . $order . '/' . 'category_code') ?>">Category Code</a></th>
                      <th ><a href="<?php echo base_url('Mst_category/index/' . $category_name . '/' . $category_code . '/' . $created_by . '/' . $start_date . '/' . $end_date . '/' . $order . '/' . 'created_on') ?>">Created On</a></th>
                      <th ><a href="<?php echo base_url('Mst_category/index/' . $category_name . '/' . $category_code . '/' . $created_by . '/' . $start_date . '/' . $end_date . '/' . $order . '/' . 'created_by') ?>">Created By </a></th>
                      <th style="color:black;">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php if(!empty($mst_category)) {
                      $seg=$this->uri->segment(10); 
                    if(is_numeric($seg)){
                      $sno = $this->uri->segment(10) + 1;
                    }else{
                      $sno = 1;                    
                    }   

                    // Showing data from data base to table using foreach by kamal on 6th june 2022

                       foreach($mst_category as $value){ ?>
                    <tr>
                        <td><?php echo $sno++;?></td>
                        <td><?php echo $value['category_name'];?></td>
                        <td><?php echo $value['category_code'];?></td>
                        
                        <td><?php echo $value['created_on'];?></td>
                        <td><?php echo $value['created_by'];?></td>
                        <td>
                        
                        <!-- Edit button for editing of data in mst category on 6th of june 2022 by kamal -->

                         <?php if (1) { ?>
                              <a href="<?php echo base_url(); ?>Mst_category/edit/<?php echo $value['category_id']; ?>" class="btn btn-sm"><img src="<?php echo base_url('assets/images/mem_edit.png'); ?>" title="Edit" class="edit" alt="Edit" width="20px"></a>
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
                <?php if ($mst_category && count($mst_category  ) > 0) { ?>
                  <span id="pagination"><?php echo ($links) ? $links : ''; ?></span>
                  <span><?php echo ($result_count)?$result_count:''; ?></span>
                  <span><?php  ?></span>
                  <?php } else { ?>
                     <h3>NO RECORD FOUND</h3> 
                    <?php } ?>
          
        <?php  if(count($mst_category  ) <1) { ?>
         <h3></h3>
        <?php } ?>
        </div>
        <!-- </div> -->
   </body>
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
      var cat_name = $('#category_name').val(); 
      var cat_type = $('#category_code').val();
      var created_by = $('#created_by').val();
      var start_date = $('#start_date').val();
      var end_date = $('#end_date').val();
      // alert(start_date);
      // alert(end_date);
      // alert($this->uri->segment(7));
      if (cat_name == '') {
        cat_name = 'NULL';
      } else {
        cat_name = btoa(cat_name); 
      }
      if (created_by=='') {
        created_by = 'NULL';
      } else {
         created_by=btoa(created_by);
      }

      if (cat_type == '') {
        cat_type = 'NULL';
      } else {
        cat_type = btoa(cat_type);
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
      window.location.replace(url + 'Mst_category/index/' + cat_name + '/' + cat_type+ '/' + created_by
      +'/'+ start_date+'/'+end_date);
      }
    }

   

    $(document).on('click', '#reset', function() {
      $('#category_name').val('');
      $('#category_code').val('');
      $('#created_by').val('');
      filter(0)
    });
  });
  
      </script>
   </html>