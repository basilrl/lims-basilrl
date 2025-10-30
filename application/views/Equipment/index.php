 <!-- Content Wrapper. Contains page content -->

 <!-- Created by kamal Singh on 6th of June 2022  -->
 <!-- APPLICATION INSTRUCTION CARE TABLE LISTING FILE -->
 <link rel="stylesheet" href="<?php echo base_url('assets/dataTables/css/dataTables.bootstrap4.min.css'); ?>">

 <script src="<?php echo base_url('assets/dataTables/js/jquery.dataTables.min.js'); ?>"></script>
 <script src="<?php echo base_url('assets/dataTables/js/dataTables.bootstrap4.min.js'); ?>"></script>

 <script src="<?php echo base_url('ckeditor') ?>/ckeditor.js"></script>


 <style>

 </style>
 <!-- links for provding the data to equipment -->
 <?php
  $eqip_name = ($this->uri->segment('3') != 'NULL') ? base64_decode($this->uri->segment('3')) : '';
  $eqip_Id_no = ($this->uri->segment('4') != 'NULL') ? base64_decode($this->uri->segment('4')) : '';
  $model = ($this->uri->segment('5') != 'NULL') ? base64_decode($this->uri->segment('5')) : '';
  $make = ($this->uri->segment('6') != 'NULL') ? base64_decode($this->uri->segment('6')) : '';
  $serial_no = ($this->uri->segment('7') != 'NULL') ? base64_decode($this->uri->segment('7')) : '';
  $calibby = ($this->uri->segment('8') != 'NULL') ? base64_decode($this->uri->segment('8')) : '';
  $due_date = ($this->uri->segment('9') != 'NULL') ? base64_decode($this->uri->segment('9')) : '';
  $calibdate = ($this->uri->segment('10') != 'NULL') ? base64_decode($this->uri->segment('10')) : '';
  $division = ($this->uri->segment('11') != 'NULL') ? base64_decode($this->uri->segment('11')) : '';
  $certi_no = ($this->uri->segment('12') != 'NULL') ? base64_decode($this->uri->segment('12')) : '';
  ?>
 <style>


 </style>
 <div class="content-wrapper">
   <section class="content-header">
     <div class="container-fluid">
       <div class="row mb-2">
         <div class="col-sm-6">
           <h1>Equipments</h1>

         </div>
       </div>
       <br>
       <div>
       
       </div>
     </div>


     <section class="content">
       <div class="container-fluid">
         <!-- /.row -->
         <div class="row">
           <div class="col-12">
             <div class="card">
               <div class="card-header">
                 <!-- ADD INSTRUCTION LINK BY KAMAL -->
                 <div class="row">
           <!-- Token to security on mst category by kamal on 6th of june 2022 -->
           <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
           <div class="col-md-2">
             <div class="form-group">
               <label>Equipment Name:</label>
               <input style="size: 0;" type="text" name="equip_name" id="equip_name" class="form-control form-control-sm" placeholder="Equipment Name" value="<?php echo $eqip_name; ?>">
             </div>
           </div>
           <div class="col-md-2">
             <div class="form-group">
               <label>Equipment Id :</label>
               <input style="size: 0;" type="text" name="equip_id_no" id="equip_id_no" class="form-control form-control-sm" placeholder="Equipment Id No..." value="<?php echo $eqip_Id_no; ?>">
             </div>
           </div>
           <div class="col-md-2">
             <div class="form-group">
               <label>Model:</label>
               <input style="size: 0;" type="text" name="model" id="model" class="form-control form-control-sm" placeholder="Model" value="<?php echo $model; ?>">
             </div>
           </div>
           <div class="col-md-2">
             <div class="form-group">
               <label>Made Up Of :</label>
               <input style="size: 0;" type="text" name="make" id="make" class="form-control form-control-sm " placeholder="Made Up Of ..." value="<?php echo $make; ?>">
             </div>
           </div>
           <div class="col-md-2">
             <div class="form-group">
               <label>Serial No:</label>
               <input style="size: 0;" type="text" name="serial" id="serial" class="form-control form-control-sm " placeholder="Serial No..." value="<?php echo $serial_no; ?>">
             </div>
           </div>
           <div class="col-md-2">
             <div class="form-group">
               <label>Calibrated By:</label>
               <input style="size: 0;" type="text" name="calibby" id="calibby" class="form-control form-control-sm " placeholder="Serial No..." value="<?php echo $calibby; ?>">
             </div>
           </div>
         </div>
         <div class="row">
                  <div class="col-md-2 mt-3">
                    
          <a class="btn btn-primary" href="<?php echo base_url(); ?>Equipment/add"><i class="fa fa-plus" aria-hidden="true"></i> Add</a>
               </div>
           <div class="col-md-2">
             <div class="form-group">
               <label>Calibration Due Date:</label>
               <input style="size: 0;" type="date" name="due_date" id="due_date" value="<?php echo $due_date ?>" class="form-control form-control-sm">
             </div>
           </div>
           <div class="col-md-2">
             <div class="form-group">
               <label>Calibration Date:</label>
               <input style="size: 0;" type="date" name="calibdate" id="calibdate" value="<?php echo $calibdate ?>" class="form-control form-control-sm ">
             </div>
           </div>       
             <div class="col-md-3">
               <label>Division:</label>
               <select name="division" id="division" class="form-control form-control-sm">
                 <option value="<?php echo $division; ?>">Select Division</option>
                 <?php foreach ($div as $key) { ?>
                   <option value="<?php echo $key->division_id; ?>"> <?php echo $key->division; ?> </option>
                 <?php } ?>
               </select>
             </div>
             <div class="col-md-3">
             <label>Certificate No:</label>
               <div class="input-group">
                
                 <input style="size: 0;" placeholder="Calibration Certificate No..." type="text" name="certi_no" id="certi_no" value="<?php echo $certi_no ?>" class="form-control form-control-sm ">
<div class="input-group-append">
<button  type="button" class="btn btn-primary btn-sm" id="search"><i class="fa fa-search" aria-hidden="true"></i></button> &nbsp;
             <button  type="button" class="btn btn-danger btn-sm " onclick="location.href='<?php echo base_url('Equipment/index'); ?>'"><i class="fa fa-trash" aria-hidden="true"></i></button>
</div>

             </div></div>

            
           


         </div>

                 <div class="card-tools">

                 </div>
               </div>
             </div>
             <!-- LISTING OF APPLICATION CARE INSTRUCTION BY KAMAL  ON 6TH JUNE 2022 -->
             <!-- <input type="hidden" id="order" value="">
              <input type="hidden" id="column" value=""> -->
             <div class="card-body table-responsive p-0">
               <table class="table table-hover text-nowrap table-sm  table-bordered" style="border-top:2px solid #ddd; padding-top:5px;">
                 <thead>
                   <tr class="text-primary bg-white">
                     <?php
                      if ($eqip_name) {
                        $eqip_name = base64_encode($eqip_name);
                      } else {
                        $eqip_name = "NULL";
                      }
                      if ($eqip_Id_no) {
                        $eqip_Id_no = base64_encode($eqip_Id_no);
                      } else {
                        $eqip_Id_no = "NULL";
                      }
                      if ($model) {
                        $model = base64_encode($model);
                      } else {
                        $model = "NULL";
                      }
                      if ($make) {
                        $make = base64_encode($make);
                      } else {
                        $make = "NULL";
                      }
                      if ($serial_no) {
                        $serial_no = base64_encode($serial_no);
                      } else {
                        $serial_no = "NULL";
                      }
                      if ($calibby) {
                        $calibby = base64_encode($calibby);
                      } else {
                        $calibby = "NULL";
                      }
                      if ($due_date) {
                        $due_date = base64_encode($due_date);
                      } else {
                        $due_date = "NULL";
                      }
                      if ($calibdate) {
                        $calibdate = base64_encode($calibdate);
                      } else {
                        $calibdate = "NULL";
                      }
                      if ($division) {
                        $division = base64_encode($division);
                      } else {
                        $division = "NULL";
                      }
                      if ($certi_no) {
                        $certi_no = base64_encode($certi_no);
                      } else {
                        $certi_no = "NULL";
                      }
                      if ($order != NULL) {
                        $order = base64_encode($order);
                      } else {
                        $order = "NULL";
                      }
                      ?>
                     <th style="color:black;">SL No.</th>
                     <th><a href="<?php echo base_url('Equipment/index/' . $eqip_name . '/' . $eqip_Id_no . '/' . $model . '/' . $make . '/' . $serial_no . '/' . $calibby . '/' . $due_date . '/' . $calibdate . '/' . $division . '/'.$certi_no.'/' . $order . '/' . 'eqip_name') ?>">Equipment Name</a></th>
                     <th><a href="<?php echo base_url('Equipment/index/' . $eqip_name . '/' . $eqip_Id_no . '/' . $model . '/' . $make . '/' . $serial_no . '/' . $calibby . '/' . $due_date . '/' . $calibdate . '/' . $division . '/'.$certi_no.'/' . $order . '/' .'eqip_Id_no') ?>">Equipment Id </a></th>
                     <th><a href="<?php echo base_url('Equipment/index/' . $eqip_name . '/' . $eqip_Id_no . '/' . $model . '/' . $make . '/' . $serial_no . '/' . $calibby . '/' . $due_date . '/' . $calibdate . '/' . $division . '/'.$certi_no.'/' . $order . '/' . 'model') ?>">Model</a></th>
                     <th><a href="<?php echo base_url('Equipment/index/' . $eqip_name . '/' . $eqip_Id_no . '/' . $model . '/' . $make . '/' . $serial_no . '/' . $calibby . '/' . $due_date . '/' . $calibdate . '/' . $division . '/'.$certi_no.'/' . $order . '/' . 'make') ?>">Make</a></th>
                     <th><a href="<?php echo base_url('Equipment/index/'. $eqip_name . '/' . $eqip_Id_no . '/' . $model . '/' . $make . '/' . $serial_no . '/' . $calibby . '/' . $due_date . '/' . $calibdate . '/' . $division . '/'.$certi_no.'/' . $order . '/' . 'serial_no') ?>">Serial No</a></th>
                     <th><a href="<?php echo base_url('Equipment/index/' . $eqip_name . '/' . $eqip_Id_no . '/' . $model . '/' . $make . '/' . $serial_no . '/' . $calibby . '/' . $due_date . '/' . $calibdate . '/' . $division . '/'.$certi_no.'/' . $order . '/' .'calib_date') ?>">Calibration Date</a></th>
                     <th><a href="<?php echo base_url('Equipment/index/' . $eqip_name . '/' . $eqip_Id_no . '/' . $model . '/' . $make . '/' . $serial_no . '/' . $calibby . '/' . $due_date . '/' . $calibdate . '/' . $division . '/'.$certi_no.'/' . $order . '/' . 'calib_due_date') ?>">Calibration Due Date</a></th>
                     <th><a href="<?php echo base_url('Equipment/index/' . $eqip_name . '/' . $eqip_Id_no . '/' . $model . '/' . $make . '/' . $serial_no . '/' . $calibby . '/' . $due_date . '/' . $calibdate . '/' . $division . '/'.$certi_no.'/' . $order . '/' . 'calibrated_by') ?>">Calibrated By</a></th>
                     <th><a href="<?php echo base_url('Equipment/index/' . $eqip_name . '/' . $eqip_Id_no . '/' . $model . '/' . $make . '/' . $serial_no . '/' . $calibby . '/' . $due_date . '/' . $calibdate . '/' . $division . '/'.$certi_no.'/' . $order . '/' . 'calibration_certificate_number') ?>">Calibrated Certificate No</a></th>
                     <!-- <th>Status</th> -->
                     <th>Action</th>
                   </tr>
                 </thead>
                 <tbody>
                   <?php if (!empty($eqip_equipments)) {
                      $seg = $this->uri->segment(15);
                      if (is_numeric($seg) && ($this->input->get('equip_name') == null || $this->input->get('equip_id_no') == null || $this->input->get('make') == null) || $this->input->get('model') == null || $this->input->get('serial') == null) {
                        $sno = $this->uri->segment(15) + 1;
                      } else {
                        $sno = 1;
                      }
                      foreach ($eqip_equipments as $value) { ?>
                       <tr>

                         <td><?php echo $sno++; ?></td>
                         <td><?php echo $value['eqip_name'] ?></td>
                         <td><?php echo $value['eqip_ID_no'] ?></td>

                         <td><?php echo $value['model'] ?></td>
                         <td><?php echo $value['make']; ?></td>
                         <td><?php echo $value['serial_no']; ?></td>
                         <td><?php echo $value['calibration_date']; ?></td>
                         <td><?php echo $value['calibration_due_date']; ?></td>

                         <td><?php echo $value['calibrated_by']; ?></td>
                         <td><?php echo $value['calibration_certificate_number']; ?></td>
                         <td>

                           <?php if (1) { ?>
                             <a href="<?php echo base_url(); ?>Equipment/edit/<?php echo $value['eqip_id']; ?>" class="btn btn-sm log_view btn-default">  <i class="fas fa-file-signature" alt="Edit"></i></a>
                             <a href="javascript:void(0)" data-bs-target="#log_model" data-bs-toggle="modal" data-id="<?php echo $value['eqip_id'] ?>" id="old_id" class="btn btn-sm log_view btn-default"><img src="<?php echo base_url('assets/images/log-view.png'); ?>" title="Add Log" width="20px" alt="Log"></a>&nbsp;&nbsp;
                             <a href="javascript:void(0)" data-bs-toggle="modal" class="btn btn-sm log_view btn-default" data-bs-target="#view_modal" title="View Log" data-id="<?php echo $value['eqip_id'] ?>" id="view_id"><i class="fa fa-eye" aria-hidden="true"></i></a>
                           <?php } ?>
                           </td>

                       </tr>
                     <?php } ?>

                   <?php } ?>
                 </tbody>
               </table>
             </div>
             <!-- The pagaintion for equipment by kamal on 22th of july 2022 -->
             <div class="card-header">
               <?php if ($eqip_equipments && count($eqip_equipments) > 0) { ?>
                 <span id="pagination"><?php echo ($links) ? $links : ''; ?></span>
                 <span><?php echo ($result_count) ? $result_count : ''; ?></span>
                 <span><?php  ?></span>
               <?php } else { ?>
                 <h3>NO RECORD FOUND</h3>
               <?php } ?>


             </div>
           </div>

           <!-- This modal is for view of the log of calibration and maintenance created by kamal singh on 7th of june 2022 -->

           <div class="modal fade" id="view_modal" tabindex="-1" role="dialog" aria-hidden="true">
             <div class="modal-dialog" role="document">
               <div class="modal-content" style="max-height: 500px;">
                 <div class="modal-header">
                   <h5 class="modal-title" id="exampleModalLabel">Equipment LOG</h5>
                   <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                   </button>
                 </div>
                 <div class="modal-body">
                   <table class="table" id="view_table">
                     <thead>
                       <tr>
                         <th>SL.No.</th>
                         <th>Action Log</th>
                         <th>Last Date</th>
                         <th>Period</th>
                         <th>Action Date</th>
                         <th>Next Date</th>
                         <?php if (exist_val('Equipment/delete_log', $this->session->userdata('permission'))) { ?>

                           <th>Action</th>
                         <?php } ?>
                       </tr>
                     </thead>
                     <tbody id="table_log"></tbody>
                   </table>
                 </div>
                 <div class="modal-footer">
                   <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                 </div>
               </div>
             </div>
           </div>


           <!-- end log view modal here  -->


           <!-- Log modal for view log of calibration and maintenance created by kamal singh on 6th of july 2022 -->
           <div class="modal fade" id="log_model" tabindex="-1">

             <div class="modal-dialog">
               <div class="modal-content">
                 <div class="modal-header">

                   <center>
                     <h4 class="modal-title "><a href="">Add Log</a></h4>
                   </center>
                   <button class="close" style="margin-left:100px;" onclick="location.href='<?php echo base_url('Equipment/index'); ?>'">&times;</button>
                 </div>
                 <div class="modal-body" style="height:430px;">
                   <center>
                     <div class="col-md-4">
                       <div class="form-group">
                         <select name="log" id="log" class="form-control">
                           <option disabled selected>Select Log</option>
                           <option value="Calibration Log" id="calib">Calibration</option>
                           <option value="Maintenance Log" id="main">Maintenance</option>
                         </select>
                       </div>
                     </div>
                   </center>


                   <!-- This is calibration log created by kamal on 22th of july 2022 -->
                   <form id="calib_log_form" enctype="multipart/form-data" method="POST" action="javascript:void(0);">
                     <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                     <div hidden class="Calibration" id="Calibration">

                       <input hidden type="text" name="log_eqip_id" id='log_eqip_id'>

                       <div class="row">


                         <div class="col-md-4">
                           <div class="form-group">
                             <label>Last Calibration Date:</label>
                             <?php echo form_input(['class' => 'form-control', 'name' => 'Last_calib_date_log', 'id' => 'Last_calib_date_log', 'type' => 'date', 'value' => set_value('Last_calib_date_log')]); ?>
                             <?php echo form_error('Last_calib_date_log', '<div class="text-danger">', '</div>'); ?>
                           </div>
                         </div>



                         <div class="col-md-8">
                           &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label>Calibration Period:</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                           &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                           &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                           <label>Calibration Time:</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                           &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                           &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                           <label>Calibration Action Date:</label>
                           <div class="input-group">
                             <select name="log_calib_time" id="log_calib_time" class="form-control" style=" width:100px; margin-left:15px; margin-right:10px;">
                               <option disabled selected>What To Add</option>
                               <option value="days">Days</option>
                               <option value="months">Months</option>
                               <option value="years">Years</option>
                             </select>
                             <!-- <div class="input-group-appand"> -->
                             <?php
                              //  echo form_input(['class' => 'form-control', 'name' => 'log_calib_period', 'id' => 'log_calib_period', 'type' => 'number',]);
                              ?>
                             <?php
                              // echo form_error('log_calib_period', '<div class="text-danger">', '</div>');
                              ?>
                             <select name="log_calib_period" id="log_calib_period" class="form-control" style=" width:100px; margin-left:15px; margin-right:10px;">
                               <option disabled selected>No Of Days/Months/Years </option>
                               <!-- <option value="">kamal</option> -->
                               <!-- <option value="">piasjdkl</option> -->

                               <!-- </div> -->
                           </div>
                         </div>

                         <div class="col-md-4">
                           <div class="form-group">
                             <label>Calibration Action Date:</label>
                             <input type="date" class="form-control" name="Action_log" id="Action_log">
                             <?php
                              // echo form_input(['class' => 'form-control', 'name' => 'Action_log', 'id' => 'Action_log', 'type' => 'date',]);
                              ?>
                             <?php echo form_error('Action_log', '<div class="text-danger">', '</div>'); ?>
                           </div>
                         </div>



                       </div>

                       <div class="row">
                         <div class="col-md-4">
                           <div class="form-group">
                             <label>Calibration Next Date:</label>
                             <!-- <input type="date" name="next_calib_log" id=""> -->
                             <?php
                              echo form_input(['class' => 'form-control', 'name' => 'next_calib_log', 'id' => 'next_calib_log', 'type' => 'date']); ?>
                             <?php
                              echo form_error('next_calib_log', '<div class="text-danger">', '</div>'); ?>
                           </div>
                         </div>

                         <div class="col-md-4">
                           <div class="form-group">
                             <label>Attached File :</label>
                             <input type="file" name="image" id="image" class="form-control">
                             <label style="color:green;">* All Type</label>
                             <?php echo form_error('image', '<div class="text-danger">', '</div>'); ?>
                           </div>
                         </div>

                       </div>
                       <div class="col-md-12">
                         <div class="form-group">
                           <label>Note:</label>
                           <?php
                            echo form_textarea(['rows' => '5', 'cols' => '10', 'class' => 'form-control', 'name' => 'note', 'id' => 'note', 'placeholder' => 'note...']);
                            ?>
                           <!-- <textarea name="note"  id="note" cols="30" rows="10" placeholder="Note..."></textarea> -->

                           <?php echo form_error('note', '<div class="text-danger">', '</div>'); ?>
                         </div>
                       </div>
                       <div class="col-md-5" style="position: relative; top:15px; left:500px;">
                         <div class="form-group">
                           <input type="submit" value="Add" name="log_add" class="btn btn-primary btn-lg">
                           <button class="btn btn-danger btn-lg"><a style="color:white;" href="<?php echo base_url('Equipment/index'); ?>">Cancel</a></button>

                         </div>
                       </div>

                     </div>
                   </form>
                   <!-- end Calibration Log area -->



                   <!-- This is maintenance log area using for modals by kamal singh on 23th of july 2022 -->
                   <form id="main_log_form" enctype="multipart/form-data">
                     <div hidden class="Maintenance" id="Maintenance">
                       <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">

                       <input type="text" hidden name="main_eqip_id" id='main_eqip_id'>
                       <div class="row">

                         <div class="col-md-4">
                           <div class="form-group">
                             <label>Last Maintenance Date:</label>
                             <?php echo form_input(['class' => 'form-control', 'name' => 'last_main_log', 'id' => 'last_main_log', 'type' => 'date', 'value' => set_value('last_main_log')]); ?>
                             <?php echo form_error('last_main_log', '<div class="text-danger">', '</div>'); ?>
                           </div>
                         </div>


                         <div class="col-md-8">
                           &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label>Maintenance Period:</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                           &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                           &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label>Maintenance Action Time:</label>
                           &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                           &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                           <label>Maintenance Action Date:</label>
                           <div class="input-group">
                             <select name="main_time_log" id="main_time_log" class="form-control" style=" width:100px; margin-left:15px; margin-right:10px;">
                               <option disabled selected>What To Add</option>
                               <option value="days">Days</option>
                               <option value="months">Months</option>
                               <option value="years">Years</option>
                             </select>
                             <?php
                              ?>
                             <?php
                              ?>

                             <select name="main_period_log" id="main_period_log" class="form-control" style=" width:100px; margin-left:15px; margin-right:10px;">
                               <option disabled selected>No Of Days/Months/Years </option>
                           </div>
                         </div>



                       </div>
                       <div class="col-md-4">
                         <div class="form-group">
                           <?php echo form_input(['class' => 'form-control', 'name' => 'Action_main_log', 'id' => 'Action_main_log', 'type' => 'date']); ?>
                           <?php echo form_error('Action_main_log', '<div class="text-danger">', '</div>'); ?>
                         </div>
                       </div>


                     </div>

                     <div class="row">
                       <div class="col-md-4">
                         <div class="form-group">
                           <label>Next Maintenance Date:</label>
                           <?php echo form_input(['class' => 'form-control', 'name' => 'next_date_log', 'id' => 'next_date_log', 'type' => 'date',]); ?>
                           <?php echo form_error('next_date_log', '<div class="text-danger">', '</div>'); ?>
                         </div>
                       </div>

                       <div class="col-md-4">
                         <div class="form-group">
                           <label>Attached File :</label>
                           <input type="file" name="image_main" id="image_main" class="form-control">
                           <label style="color:green;">* All Type</label>
                           <?php echo form_error('image_main', '<div class="text-danger">', '</div>'); ?>
                         </div>
                       </div>

                     </div>
                     <div class="col-md-12">
                       <div class="form-group">
                         <label>Note:</label>
                         <?php echo
                          form_textarea(['rows' => '5', 'cols' => '10', 'class' => 'form-control', 'name' => 'note2', 'id' => 'note2', 'placeholder' => 'note...',]);
                          ?>
                         <?php echo form_error('note2', '<div class="text-danger">', '</div>'); ?>
                       </div>
                     </div>
                     <div class="col-md-5" style="position: relative; top:15px; left:500px;">
                       <div class="form-group">
                         <input type="submit" value="Add" name="btnedit" class="btn btn-primary btn-lg">
                         <button class="btn btn-danger btn-lg"><a style="color:white;" href="<?php echo base_url('Equipment/index'); ?>">Cancel</a></button>

                       </div>
                     </div>

                 </div>
                 </form>

                 <!-- end Maintenance Log area -->


               </div>
             </div>
           </div>
         </div>
       </div>


       <!-- script for datepicker cdn will not work without internet by kamal singh -->
       <script src="https://cdnjs.cloudflare.com/ajax/libs/datepicker/1.0.10/datepicker.min.js"></script>
       <!-- script for moment used in set date by kamal singh-->
       <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.3/moment.min.js"></script>

       <!-- script for calibration date set in the action column by kamal singh -->
       <script>
         $("#Last_calib_date_log").datepicker({
           dateFormat: 'yy-mm-dd',
           minDate: new Date(),
         });



         $(document).on('change', '#log_calib_time', function() {
           var to_add = $(this).val();
           $('#log_calib_period').empty();
           $('#log_calib_period').append($('<option></option>').attr({
             disabled: 'disabled',
             selected: 'selected'
           }).text('Select no days/month/year'));
           if (to_add == "days") {
             for (var i = 1; i <= 31; i++) {
               $('#log_calib_period').append($('<option></option>').attr('value', i).text(i));
             }
           } else if (to_add == "months") {
             for (var i = 1; i <= 12; i++) {
               $('#log_calib_period').append($('<option></option>').attr('value', i).text(i));
             }
           } else {
             for (var i = 1; i <= 5; i++) {
               $('#log_calib_period').append($('<option></option>').attr('value', i).text(i));
             }
           }
         });

         $('#Last_calib_date_log').change(function() {
           var start_date = $(this).val();
           var to_add = $('#log_calib_time').val();
           var no_days = $('#log_calib_period').val();
           if (to_add == "days") {
             add_days(start_date, no_days);
           } else {
             add_month(start_date, no_days);
           }
         });

         $('#log_calib_period').change(function() {
           var start_date = $('#Last_calib_date_log').val();
           var no_days = $(this).val();
           var to_add = $('#log_calib_time').val();

           if (to_add == "days") {
             add_days(start_date, no_days);
           } else if (to_add == "months") {
             add_month(start_date, no_days);
           } else {
             add_year(start_date, no_days);
           }
         });

         function add_month(start_date, to_add) {
           var new_date = moment(start_date, "YYYY-MM-DD").add(to_add, 'months').format('YYYY-MM-DD');
           $('#Action_log').val(new_date);
         }

         function add_days(start_date, to_add) {
           var new_date = moment(start_date, "YYYY-MM-DD").add(to_add, 'days').format('YYYY-MM-DD');
           $('#Action_log').val(new_date);
         }

         function add_year(start_date, to_add) {
           var new_date = moment(start_date, "YYYY-MM-DD").add(to_add, 'year').format('YYYY-MM-DD');
           $('#Action_log').val(new_date);
         }
       </script>
       .
       <!-- end setting date in the calibration  -->

       <!-- script for the maintance log this is settting the date in the action log by kamal singh -->
       <script>
         $("#last_main_log").datepicker({
           dateFormat: 'yy-mm-dd',
           minDate: new Date(),
         });


         $(document).on('change', '#main_time_log', function() {
           var to_add2 = $(this).val();
           $('#main_period_log').empty();
           $('#main_period_log').append($('<option></option>').attr({
             disabled: 'disabled',
             selected: 'selected'
           }).text('Select no days/month/year'));
           if (to_add2 == "days") {
             for (var i = 1; i <= 31; i++) {
               $('#main_period_log').append($('<option></option>').attr('value', i).text(i));
             }
           } else if (to_add2 == "months") {
             for (var i = 1; i <= 12; i++) {
               $('#main_period_log').append($('<option></option>').attr('value', i).text(i));
             }
           } else {
             for (var i = 1; i <= 5; i++) {
               $('#main_period_log').append($('<option></option>').attr('value', i).text(i));
             }
           }
         });

         $('#last_main_log').change(function() {
           var start_date2 = $(this).val();
           var to_add2 = $('#main_time_log').val();
           var no_days2 = $('#main_period_log').val();
           if (to_add2 == "days") {
             add_days2(start_date2, no_days2);
           } else {
             add_month2(start_date2, no_days2);
           }
         });

         $('#main_period_log').change(function() {
           var start_date2 = $('#last_main_log').val();
           var no_days2 = $(this).val();
           var to_add2 = $('#main_time_log').val();

           if (to_add2 == "days") {
             add_days2(start_date2, no_days2);
           } else if (to_add2 == "months") {
             add_month2(start_date2, no_days2);
           } else {
             add_year2(start_date2, no_days2);
           }
         });

         function add_month2(start_date2, to_add2) {
           var new_date2 = moment(start_date2, "YYYY-MM-DD").add(to_add2, 'months').format('YYYY-MM-DD');
           $('#Action_main_log').val(new_date2);
         }

         function add_days2(start_date2, to_add2) {
           var new_date2 = moment(start_date2, "YYYY-MM-DD").add(to_add2, 'days').format('YYYY-MM-DD');
           $('#Action_main_log').val(new_date2);
         }

         function add_year2(start_date2, to_add2) {
           var new_date2 = moment(start_date2, "YYYY-MM-DD").add(to_add2, 'year').format('YYYY-MM-DD');
           $('#Action_main_log').val(new_date2);
         }
       </script>

       <!-- end setting date in the maintenance action log  -->



       <script>
         // javascript for select box in the modal by kamal singh
         //  This is showing the view after select of log type

         $(document).ready(function() {
           $(document).on('change', '#log', function() {
             var log_type = $('#log option:selected').val();
             if (log_type == 'Calibration Log') {
               $('#Calibration').removeAttr('hidden');
               $('#Maintenance').attr('hidden', true)
             } else if (log_type == 'Maintenance Log') {
               $('#Maintenance').removeAttr('hidden');
               $('#Calibration').attr('hidden', true)
               //  alert('Maintenance Log is Working welll');
             }
           });
         });
       </script>

       <script>
         // providing the equipment id to the logs by kamal singh on 22th of juluy 2022

         $(document).on('click', '#old_id', function() {
           var id = $(this).data("id");
           $('#log_eqip_id').val(id);
           $('#main_eqip_id').val(id);
         });
       </script>



       <!-- ajax function for the calibration field in the equipment log by kamal singh -->
       <script>
         $('#calib_log_form').submit(function(e) {
           e.preventDefault();
           for (instance in CKEDITOR.instances) {
             CKEDITOR.instances[instance].updateElement();
           }
           var form = $(this);
           $.ajax({
             url: "<?php echo base_url('Equipment/create_log'); ?>",
             type: 'post',
             async: false,
             data: new FormData(this),
             cache: false,
             contentType: false,
             processData: false,
             success: function(response) {

               var result = $.parseJSON(response);
               if (result.status == 1) {
                 window.location.reload();
               } else if (result.status == 0) {
                 $.notify(result.msg, 'error');
               }
               if (result.status == 3) {
                 $.notify(result.msg, 'error');
               }

             },
             error: function() {
               alert('Error');
             }
           });
         });

         // end ajax for calibrartion field 

         //  ajax for the maintenance log it is adding the log data into the datebase
       </script>
       <script>
         $('#main_log_form').submit(function(e) {
           e.preventDefault();
           for (instance in CKEDITOR.instances) {
             CKEDITOR.instances[instance].updateElement();
           }
           var form = $(this);
           $.ajax({
             url: "<?php echo base_url('Equipment/create_main_log'); ?>",
             type: 'post',
             async: false,
             data: new FormData(this),
             cache: false,
             contentType: false,
             processData: false,
             success: function(response) {

               var result = $.parseJSON(response);
               if (result.status == 1) {
                 window.location.reload();
               } else if (result.status == 0) {
                 $.notify(result.msg, 'error');
               }
               if (result.status == 3) {
                 $.notify(result.msg, 'error');
               }

             },
             error: function() {
               alert('Error');
             }
           });
         });
       </script>


       <!-- end ajax maintenanc log area -->

       <script>
         // javascript for searching filter on 22th of july 2022 by kamal
         $(document).ready(function() {
           const url = $('body').data('url');
           $(document).on('click', '#search', function() {
             filter();
           });

           function filter() {
             var eqip_name = $('#equip_name').val(); //department name
             var eqip_id = $('#equip_id_no').val();
             var model = $('#model').val();
             var make = $('#make').val();
             var serial = $('#serial').val();
             var calibby = $('#calibby').val();
             var due_date = $('#due_date').val();
             var calibdate = $('#calibdate').val();
             var division = $('#division').val();
             var certi_no = $('#certi_no').val();
             if (division == '') {
               division = 'NULL';
             } else {
               division = btoa(division);
             }
             if (eqip_name == '') {
               eqip_name = 'NULL';
             } else {
               eqip_name = btoa(eqip_name);
             }

             if (eqip_id == '') {
               eqip_id = 'NULL';
             } else {
               eqip_id = btoa(eqip_id);
             }

             if (model == '') {
               model = 'NULL';
             } else {
               model = btoa(model);
             }
             if (make == '') {
               make = 'NULL';
             } else {
               make = btoa(make);
             }
             if (serial == '') {
               serial = 'NULL';
             } else {
               serial = btoa(serial);
             }

             if (calibby == '') {
              calibby = 'NULL';
             } else {
              calibby = btoa(calibby);
             }

             if (due_date == '') {
              due_date = 'NULL';
             } else {
              due_date = btoa(due_date);
             }
             if (calibdate == '') {
              calibdate = 'NULL';
             } else {
              calibdate = btoa(calibdate);
             }
             if (certi_no == '') {
              certi_no = 'NULL';
             } else {
              certi_no = btoa(certi_no);
             }
            //  alert("kamal");
            //  if (start_date == 'NULL' && end_date != 'NULL') {
            //    alert("Pleasure Select Start Date");
            //  } else if (start_date != 'NULL' && end_date == 'NULL') {
            //    alert("Pleasure Select End Date");
            //  }
            //  // Setting the link in url according to searching filter on application care instruction on 6th of june 2022
            //  else {
               window.location.replace(url + 'Equipment/index/' + eqip_name + '/' + eqip_id + '/' + model + '/' + make + '/' + serial +
                 '/' + calibby + '/' + due_date + '/' + calibdate + '/' + division +'/'+ certi_no);
            //  }
           }


           $(document).on('click', '#reset', function() {
             $('#equip_name').val('');
             $('#equip_id_no').val('');
             $('#model').val('');
             $('#make').val('');
             $('#serial').val('');
             $('#calibby').val('');
             $('#calibdate').val('');
             $('#due_date').val('');
             $('#division').val('');
             $('#certi_no').val('');
             filter(0)
           });
         });


         //  CKEDITOR for textarea it is providing the extra features for textarea 
         CKEDITOR.config.enterMode = CKEDITOR.ENTER_BR;
         CKEDITOR.replace('note');
         CKEDITOR.replace('note2');
       </script>



       <!-- ajax function for the listing of the view log created by kamal singh on 6th of july 2022 -->
       <script>
         $(document).ready(function() {

           const url = $('body').data('url');
           const _tokken = $('meta[name="_tokken"]').attr('value');
           // Ajax call to get log
           $('.log_view').click(function() {

             var id = $(this).data('id');
             $.ajax({
               type: 'post',
               url: url + 'Equipment/get_log_Data',
               data: {
                 _tokken: _tokken,
                 id: id
               },
               beforeSend: function() {
                 // pagination and searching with the help of DataTable 
                 $('#table_log').html('');
                 if ($.fn.DataTable.isDataTable('#view_table')) {
                   $('#view_table').DataTable().destroy();
                 }
               },
               success: function(data) {
                 var data = $.parseJSON(data);
                 var value = '';
                 sno = Number();
                 $.each(data, function(i, v) {
                   // extending data on view listing using ajax 
                   sno += 1;
                   //  var id=v.log
                   var Action_log = v.action;
                   var Last_date = v.last_date;
                   var Period = v.period;
                   var Action_Date = v.action_date;
                   var Next_date = v.next_date;
                   var log_id = v.log_id;
                   value += '<tr>';
                   value += '<td>' + sno + '</td>';
                   //  value += '<td>' + log_id + '</td>';
                   value += '<td>' + Action_log + '</td>';
                   value += '<td>' + Last_date + '</td>';
                   value += '<td>' + Period + '</td>';
                   value += '<td>' + Action_Date + '</td>';
                   value += '<td>' + Next_date + '</td>';
                   <?php if (exist_val('Equipment/delete_log', $this->session->userdata('permission'))) { ?>
                     value += '<td>' + "<button class='btn btn-danger delete_log' onclick='abc(" + log_id + ")'><i class='fa fa-trash'></i></button>" + '</td>';
                   <?php } ?>
                   value += '</tr>';


                 });
                 $('#table_log').html(value);
                 $('#view_table').DataTable();
               }
             });
           });
           // ajax call to get log ends here
         });

         //  delete function for log view in equipment onyl access to the super admin created by kamal on 7th of july
         function abc(log_id) {
           const url = $('body').data('url');
           const _tokken = $('meta[name="_tokken"]').attr('value');
           $.ajax({
             url: url + 'Equipment/delete_log',
             type: 'post',
             data: {
               _tokken: _tokken,
               log_id: log_id
             },
             success: function(response) {
               window.location.reload(1);
             }
           });
         }
         //  end ajax call for delete log in equipment
       </script>


       </body>

       </html>