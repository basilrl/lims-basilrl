 <!-- Content Wrapper. Contains page content -->

 <!-- Created by kamal Singh on 6th of June 2022  -->
 <!-- APPLICATION INSTRUCTION CARE TABLE LISTING FILE -->



 <?php
  $instruction_name = ($this->uri->segment('3') != 'NULL') ? base64_decode($this->uri->segment('3')) : '';
  $instruction_type = ($this->uri->segment('4') != 'NULL') ? base64_decode($this->uri->segment('4')) : '';
  $care_wording = ($this->uri->segment('5') != 'NULL') ? base64_decode($this->uri->segment('5')) : '';
  ?>
 <style>
   @media screen and (min-width: 480px) {
     input {
       /* display:block; */

     }
   }
 </style>
 <div class="content-wrapper">
   <section class="content-header">
     <div class="container-fluid">
       <div class="row">
         <div class="col-sm-12 text-center">
           <div class="float-left mt-3">
             <a href="<?php echo base_url(); ?>Care/add" class="btn btn-sm btn-primary "> <i class="fa fa-plus"></i> Add</a>

           </div>
           <h1 class="text-bold mt-3 mb-3">APPLICATION CARE INSTRUCTION</h1>
         </div>
       </div>
     </div>
     <div class="container-fluid jumbotron p-3">
       <div class="row">
       <div class="col-md-3"></div>
         <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
         <div class="col-md-3">
           <!-- SEARCH FILTER BY KAMAL -->

           <input type="text" name="instruction_name" id="instruction" value="<?php echo $instruction_name; ?>" class="form-control form-control-sm" placeholder="Instruction Name...">

         </div>
         <div class="col-md-3">
           <input type="text" name="instruction_type" id="instruction_type" value="<?php echo $instruction_type; ?>" class="form-control form-control-sm" placeholder="Instruction Type...">

         </div>
         <div class="col-md-3">
           <div class="input-group">
             <input type=" text" name="care_wording" id="care_wording" value="<?php echo $care_wording; ?>" class="form-control form-control-sm" placeholder="Care Wording...">
             <div class="input-group-append">
               <button onclick="searchfilter();" type="button" class="btn btn-sm btn-secondary" title="Search">
                 <i class="fa fa-search"></i>
               </button>
               <button type="button" class="btn btn-sm btn-danger ml-1" onclick="location.href='<?php echo base_url('care/index'); ?>'"><i class="fa fa-trash" title="Clear"></i></button>


             </div>
           </div>

         </div>
       </div>
     </div>
    <div class="container-fluid">
                 <div class="row">
                   <div class="col-12">
                     <div class="card">
                       <input type="hidden" id="order" value="">
                       <input type="hidden" id="column" value="">
                       <div class="table-responsive p-2">
                         <table class="table table-sm">
                           <thead>
                             <tr class="text-primary">
                               <th style="color:black;">SL No.</th>
                               <th>Instruction Name</th>
                               <th>Instruction Type</th>
                               <th>Care Wording</th>
                               <th>Instruction Image</th>
                               <th>Created On</th>
                               <th>Created By</th>
                               <th>Priority Order</th>
                               <th style="color:black;">Action</th>
                             </tr>
                           </thead>
                           <tbody>
                             <?php if (!empty($application_care_instruction)) {
                                $seg = $this->uri->segment(6);
                                if (is_numeric($seg) && ($this->input->get('name') == null || $this->input->get('type') == null || $this->input->get('wording  ') == null)) {
                                  $sno = $this->uri->segment(6) + 1;
                                } else {
                                  $sno = 1;
                                }

                                foreach ($application_care_instruction as $value) { ?>
                                 <tr>
                                   <td><?php echo $sno++; ?></td>
                                   <td><?php echo $value['instruction_name'] ?></td>
                                   <td><?php echo $value['instruction_type'] ?></td>

                                   <td><?php echo $value['care_wording'] ?></td>
                                   <td> <img style="width:50%; height:40px;" src="<?php echo getS3Url2($value['instruction_image']) ?>" alt="" srcset=""> </td>
                                   <!-- <td> <?php echo getS3Url2($value['instruction_image']) ?> </td> -->
                                   <td><?php echo $value['created_on']; ?></td>
                                   <td><?php echo $value['created_by']; ?></td>

                                   <td><?php echo $value['priority_order']; ?></td>
                                   <td>

                                     <?php if (1) { ?>
                                       <a href="<?php echo base_url(); ?>Care/edit/<?php echo $value['instruction_id']; ?>" class="btn btn-sm"><i class="fa fa-edit" title="Edit" class="edit" alt="Edit"></i></a>
                                     <?php } ?>

                                     <!-- <?php if ((exist_val('branch', $this->session->userdata('permission')))) { ?>
                            <?php } ?> -->
                                   </td>

                                 </tr>
                               <?php }

                                ?>

                             <?php } ?>
                           </tbody>
                         </table>

                       </div>
                       <!-- PAGINATION BY KAMAL -->
                       <div class="card-header">
                         <?php if ($application_care_instruction && count($application_care_instruction) > 0) { ?>
                           <span id="pagination"><?php echo ($links) ? $links : ''; ?></span>
                           <span><?php  ?></span>
                         <?php } else { ?>
                           <h3>NO RECORD FOUND</h3>
                         <?php } ?>

                         <?php if (count($application_care_instruction) < 1) { ?>
                           <h3>NO RECORD FOUND</h3>
                         <?php } ?>

                       </div>
                     </div>
                   </div>
                         </div>
                         </div>
                         
 </section>
                          
                   <script>
                     $(document).ready(function() {
                       const url = $('body').data('url');

                       $(document).on('click', '#search', function() {
                         filter();
                       });

                       function filter() {
                         var inst_name = $('#instruction').val(); //department name
                         var inst_type = $('#instruction_type').val();
                         var care_wor = $('#care_wording').val();

                         if (inst_name == '') {
                           inst_name = 'NULL';
                         } else {
                           inst_name = btoa(inst_name);

                         }

                         if (inst_type == '') {
                           inst_type = 'NULL';
                         } else {
                           inst_type = btoa(inst_type);
                         }


                         if (care_wor == '') {
                           care_wor = 'NULL';
                         } else {
                           care_wor = btoa(care_wor);
                         }

                         window.location.replace(url + 'care/index/' + inst_name + '/' + inst_type + '/' + care_wor);
                       }


                       $(document).on('click', '#reset', function() {
                         $('#instruction_name').val('');
                         $('#instruction_type').val('');
                         $('#care_woding').val('');
                         filter(0)
                       });
                     });
                   </script>
                   </body>

                   </html>