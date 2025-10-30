<style>
  form .error {
    color: #ff0000;
    margin-top: 0;
  }
</style>


<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>CUSTOMERS</h1>
        </div>
        <div class="col-sm-6">
          <!--            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Simple Tables</li>
            </ol>-->
        </div>
      </div>
      <!-- /.card-header -->

      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">

              <div class="row">
                <div class="col-sm-10">
                <?php if(exist_val('Customers/add_customers',$this->session->userdata('permission'))){ ?>
                  <a class="btn btn-sm btn-primary add" href="<?php echo base_url('add_customers') ?>">Add New</a>
                  <?php }?>
                </div>

<!-- EXCEL EXPORT UPDATE 12-04-2021 -->
                <?php if(exist_val('Customers/export_customers',$this->session->userdata('permission'))){ ?>
                  <div class="col-sm-2"> 
                          <a href="<?php echo base_url('customer_management/Customers/export_customers')?>" class="btn btn-md btn-success" title="Export Customers">
                          <i class="fas fa-file-excel"></i> 
                          </a>
                  </div>
                  <?php }?>

<!-- END -->
              </div>
              <hr>
              <div class="row">

                <div class="col-sm-3">
                  <input class="customer_id" type="hidden" value="<?php echo ($customer_id) ? $customer_id : '' ?>" name="customer_id">
                  <input class="form-control form-control-sm  input-sm customer_name" value="<?php echo ($customer_name) ? $customer_name->customer_name : ''; ?>" name="customer_name" type="text" placeholder="Type Customer Name" autocomplete="off">
                  <ul class="list-group-item customer_list" style="display:none">
                  </ul>
                </div>
                <div class="col-sm-2">
                  <select name="" class="form-control form-control-sm customer_type">
                    <?php echo ($customer_type) ? $customer_type : ""; ?>
                    <option value="">Select Customer Type</option>
                    <option value="Factory" <?php echo ($customer_type == 'Factory') ? "selected" : ""; ?>>Factory</option>
                    <option value="Buyer" <?php echo ($customer_type == 'Buyer') ? "selected" : ""; ?>>Buyer</option>
                    <option value="Agent" <?php echo ($customer_type == 'Agent') ? "selected" : ""; ?>>Agent</option>
                    <option value="Thirdparty" <?php echo ($customer_type == 'Thirdparty') ? "selected" : ""; ?>>Thirdparty</option>
                  </select>
                </div>

                <div class="col-sm-2">
                  <select name="" class="form-control form-control-sm accope_customer">
                    <?php echo ($accope_customer) ? $accope_customer : ""; ?>
                    <option value="">Select Accop Customers</option>
                    <option value="1" <?php echo ($accope_customer == '1') ? "selected" : ""; ?>>MARKED</option>
                    <option value="0" <?php echo ($accope_customer == '0') ? "selected" : ""; ?>>UN-MARKED</option>
                  </select>
                </div>
                <div class="col-sm-3">
                  <input value="<?php echo (($search != 'NULL') ? $search : ""); ?>" id="search" class="form-control form-control-sm" type="text" placeholder="Search" aria-label="Search" autocomplete="off">
                </div>

                <div class="col-sm-2">
                  <button onclick="searchfilter();" type="button" class="btn btn-sm btn-primary">SEARCH</button>
                  <a class="btn btn-sm btn-primary" href="<?php echo base_url('customers'); ?>">CLEAR</a>
                </div>
              </div>



              <div class="input-group input-group-md" style="width: 150px;">
                <div class="input-group-append">
                </div>
              </div>
            </div>




            <div class="table-responsive small">
              <table id="" class="table table-sm table-hovered">
                <thead>
                  <tr>

                    <?php
                    if ($search) {
                      $search = base64_encode($search);
                    } else {
                      $search = "NULL";
                    }
                    if ($customer_id != "") {
                      $customer_id = $customer_id;
                    } else {
                      $customer_id = "NULL";
                    }
                    if ($customer_type != "") {
                      $customer_type = $customer_type;
                    } else {
                      $customer_type = "NULL";
                    }
                    if ($accope_customer != "") {
                      $accope_customer = $accope_customer;
                    } else {
                      $accope_customer = "NULL";
                    }
                    if ($order != "") {
                      $order = $order;
                    } else {
                      $order = "NULL";
                    }

                    ?>
                    <th scope="col"><a href="">SL NO.</a></th>
                    <th scope="col"><a href="<?php echo base_url('customers/' . $customer_id . '/' . $customer_type . '/' .$accope_customer . '/' .$search . '/' .base64_encode('cust.customer_name').'/'. $order) ?>">CUSTOMER NAME</a></th>
                    <th scope="col"><a href="<?php echo base_url('customers/' . $customer_id . '/' . $customer_type . '/' .$accope_customer . '/' .$search . '/' .base64_encode('cust.customer_code').'/'. $order) ?>">CUSTOMER CODE</a></th>
                    <th scope="col"><a href="<?php echo base_url('customers/' . $customer_id . '/' . $customer_type . '/' .$accope_customer . '/' .$search . '/' .base64_encode('cust.customer_type').'/'. $order) ?>">CUSTOMER TYPE</a></th>
                    <th scope="col"><a href="<?php echo base_url('customers/' . $customer_id . '/' . $customer_type . '/' .$accope_customer . '/' .$search . '/' .base64_encode('cust.gstin').'/'. $order) ?>">GSTIN</a></th>
                    <th scope="col"><a href="<?php echo base_url('customers/' . $customer_id . '/' . $customer_type . '/' .$accope_customer . '/' .$search . '/' .base64_encode('cust.email').'/'. $order) ?>">EMAIL</a></th>
                    <th scope="col"><a href="<?php echo base_url('customers/' . $customer_id . '/' . $customer_type . '/' .$accope_customer . '/' .$search . '/' .base64_encode('country.country_name').'/'. $order) ?>">COUNTRY</a></th>
                    <th scope="col"><a href="<?php echo base_url('customers/' . $customer_id . '/' . $customer_type . '/' .$accope_customer . '/' .$search . '/' .base64_encode('cust.telephone').'/'. $order) ?>">TELEPHONE</a></th>

                    <th scope="col"><a href="<?php echo base_url('customers/' . $customer_id . '/' . $customer_type . '/' .$accope_customer . '/' .$search . '/' .base64_encode('cust.customer_type').'/'. $order) ?>">ACCOP/NOT-ACCOP</a></th>

                    <th scope="col"><a href="<?php echo base_url('customers/' . $customer_id . '/' . $customer_type . '/' .$accope_customer . '/' .$search . '/' .base64_encode('cust.created_by').'/'. $order) ?>">CREATED BY</a></th>
                    <th scope="col"><a href="<?php echo base_url('customers/' . $customer_id . '/' . $customer_type . '/' .$accope_customer . '/' .$search . '/' .base64_encode('cust.isactive').'/'. $order) ?>">STATUS</a></th>
                    <th scope="col"><a href="<?php echo base_url('customers/' . $customer_id . '/' . $customer_type . '/' .$accope_customer . '/' .$search . '/' .base64_encode('cust.created_on').'/'. $order) ?>">CREATED ON</a></th>
                    <th scope="col"><a href="">ACTION</a></th>

                  </tr>

                </thead>
                <tbody>
                  <?php $sn = $this->uri->segment('8') + 1;
                  if ($customers_list && count($customers_list))
                    foreach ($customers_list as $key => $item) { {

                  ?>
                      <tr>
                        <td><?php echo $sn; ?></td>
                        <td><?php echo $item->customer_name; ?></td>
                        <td><?php echo $item->customer_code; ?></td>
                        <td><?php echo $item->customer_type; ?></td>
                        <td><?php echo $item->gstin; ?></td>
                        <td><?php echo $item->email; ?></td>
                        <td><?php echo $item->country_name; ?></td>
                        <td><?php echo $item->telephone; ?></td>
                        <td><?php echo ($item->accop_cust=='1')? "MARKED": "UN-MARKED" ?></td>
                        <td><?php echo $item->created_by; ?></td>
                        <td><?php echo $item->status; ?></td>
                        <td><?php echo $item->created_on; ?></td>
                        <td>

                        <?php if(exist_val('Customers/view_cust_details',$this->session->userdata('permission'))){ ?>

                        <a class="btn btn-sm view_customers_details" title="View Customers Details" data-id="<?php echo $item->customer_id ?>" data-bs-toggle="modal" data-bs-target=".view_customer_dets">
                        <img src="<?php echo base_url('assets/images/view.png')?>" alt="view customer">
                        </a>
                        <?php }?>
                        <?php if(exist_val('Customers/edit_customers',$this->session->userdata('permission'))){ ?>

                          <a href="<?php echo base_url('edit_customers/' . $item->customer_id) ?>"><img src="<?php echo base_url('assets/images/mem_edit.png') ?>" alt="Edit" title="Edit Customer"></a>
                    <?php }?>


                    <?php if(exist_val('Customers/add_contact',$this->session->userdata('permission'))){ ?>

                          <a data-id="<?php echo $item->customer_id ?>" data-bs-toggle="modal" data-bs-target=".add_contact" style="cursor:pointer" class="manage_contact"><img src="<?php echo base_url('assets/images/manage_contact.png') ?>" alt="manage conatact" title="Add Contact"></a>
                  <?php }?>

                  <?php if(exist_val('Customers/load_contacts',$this->session->userdata('permission'))){ ?>
                          <a data-id="<?php echo $item->customer_id ?>" data-bs-toggle="modal" data-bs-target=".view_contact" style="cursor:pointer" class="load_contact"><img src="<?php echo base_url('assets/images/view-clients.png') ?>" alt="contacts list" title="View Contacts"></a>
                   <?php }?>


                   <?php if(exist_val('Customers/add_communication',$this->session->userdata('permission'))){ ?>
                          <a data-id="<?php echo $item->customer_id ?>" data-bs-toggle="modal" data-bs-target=".add_communications" style="cursor:pointer" class="communication_btn"><img src="<?php echo base_url('assets/images/edit_communication.png') ?>" alt="add communications" title="Add Communication"></a>
                    <?php }?>


                    <?php if(exist_val('Customers/load_communications',$this->session->userdata('permission'))){ ?>
                          <a data-id="<?php echo $item->customer_id ?>" data-bs-toggle="modal" data-bs-target=".manage_communications" style="cursor:pointer" class="communication_mange"><img src="<?php echo base_url('assets/images/communication.png') ?>" alt="View Communications" title="View Communications"></a>
                       <?php }?>

                       <?php if(exist_val('Customers/add_opportunity',$this->session->userdata('permission'))){ ?>

                          <a data-id="<?php echo $item->customer_id ?>" data-bs-toggle="modal" data-bs-target=".add_opportunity" style="cursor:pointer" class="add_opportunity_new"><img src="<?php echo base_url('assets/images/opportunity.png') ?>" alt="Add New Opportunity" title="Add Opportunity"></a>
                          <?php }?>

                          <?php if(exist_val('Customers/load_oportunity',$this->session->userdata('permission'))){ ?>   

                          <a data-id="<?php echo $item->customer_id ?>" data-bs-toggle="modal" data-bs-target=".manage_opportunity" style="cursor:pointer" class="manage_opportunity_btn"><img src="<?php echo base_url('assets/images/edit_opportunity.png') ?>" alt="View Opportunity" title="View Opportunity"></a>

                          <?php }?>

                          <?php if(exist_val('Customers/manage_relationship_type',$this->session->userdata('permission'))){ ?>   

                            <a data-id="<?php echo $item->customer_id ?>" data-type="<?php echo $item->customer_type ?>" data-bs-toggle="modal" data-bs-target=".manage_customers_relations" style="cursor:pointer" class="manage_customers_relation"><img src="<?php echo base_url('assets/images/management.gif') ?>" alt="Manage Customers" title="Manage customers"></a>
                            <?php }?>

                            <?php if(exist_val('Customers/mark_cust',$this->session->userdata('permission'))){ ?> 
                            <?php if ($item->accop_cust > 0) { ?>
                            <a href="<?php echo base_url('customer_management/Customers/mark_cust/'.base64_encode($item->customer_id)); ?>" style="cursor:pointer"><img src="<?php echo base_url('assets/images/flag_green.png') ?>" alt="Manage Customers" title="MARK CUSTOMER ACCOPS"></a>
                            <?php }else{ ?>
                              <a href="<?php echo base_url('customer_management/Customers/mark_cust/'.base64_encode($item->customer_id)); ?>" style="cursor:pointer"><img src="<?php echo base_url('assets/images/flag-disable.png') ?>" alt="Manage Customers" title="MARK CUSTOMER ACCOPS"></a>
                            <?php } ?>
                            <?php } ?>


                            <?php if(exist_val('Customers/view_quotations',$this->session->userdata('permission'))){ ?>  
                            <a href="javascript:void(0)" data-id="<?php echo $item->customer_id ?>" class="view_quote" data-bs-toggle='modal' data-bs-target='#view_quotation' class="btn btn-sm" title="View Quotes"><img src="<?php echo base_url('assets/images/icon/view.png'); ?>" alt="Log view" width="20px"></a>

                            <?php } ?>


                            <?php if(exist_val('Customers/get_customer_log',$this->session->userdata('permission'))){ ?>  
                            <a href="javascript:void(0)" data-id="<?php echo $item->customer_id ?>" class="log_view" data-bs-toggle='modal' data-bs-target='#exampleModal' class="btn btn-sm" title="Log View"><img src="<?php echo base_url('assets/images/log-view.png'); ?>" alt="Log view" width="20px"></a>

                            <?php } ?>

                           
                        </td>
                      </tr>
                  <?php $sn++;
                      }
                    } ?>

                </tbody>
              </table>
            </div>


            <!-- Pagination -->
            <div class="card-header">
             
                    
              <?php if($customers_list && count($customers_list)>0){?>
                <span><?php echo $links ?></span>
                <span><?php echo $result_count; ?></span>
                <?php } else {?>
                  <span>NO RECORD FOUND</span>
                  <?php }?>
              
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
      </div>
      <!-- /.row -->
    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->

</div>



<!-- add contact -->
<div class="modal fade bd-example-modal-lg add_contact" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Contact</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="manage_contact" name="manage_contact" action="javascript:void(0);">
          <input type="hidden" name="contacts_customer_id" class="contacts_customer_id">
          <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">

          <div class="row p-2 errors">


          </div>

          <div class="row p-2">
            <div class="col-sm-6">
              <label for="">Salutation:</label>
              <input type="text" name="contact_salutation" class="form-control form-control-sm" placeholder="" value="" autocomplete="off">
            </div>

            <div class="col-sm-6">
              <label for="">Country:</label>
              <input class="country_id" type="hidden" value="" name="country_id">
              <input type="text" name="country_name" class="form-control form-control-sm country_name" value="" autocomplete="off" placeholder="Type country name....">
              <ul class="list-group-item country_list" style="display:none">
              </ul>
            </div>
          </div>

          <div class="row p-2">
            <div class="col-sm-6">
              <label for="">Contact Name:</label>
              <input type="text" name="contact_name" class="form-control form-control-sm" placeholder="" value="" autocomplete="off">

            </div>

            <div class="col-sm-6">
              <label for="">State:</label>
              <input class="cust_customers_province_id state_id" type="hidden" value="" name="state_id">
              <input type="text" name="state_name" class="form-control form-control-sm state_name" placeholder="Type state name.." value="" autocomplete="off">
              <ul class="list-group-item state_list" style="display:none">
              </ul>
            </div>
          </div>

          <div class="row p-2">
            <div class="col-sm-6">
              <label for="">Designation:</label>
              <input type="text" name="contacts_designation_id" class="form-control form-control-sm" placeholder="" value="" autocomplete="off">
            </div>

            <div class="col-sm-6">
              <label for="">Type:</label>
              <select name="type" id="" class="form-control form-control-sm">
                <option value="" selected disabled>Select</option>
                <option value="0">None</option>
                <option value="1">Technical</option>
                <option value="2">Report</option>
                <option value="3">Invoice</option>
                <option value="4">Payment follow-up</option>
                <option value="5">Alternative</option>
                <option value="6">Invoice follow-up</option>
                <option value="7">site/sampling</option>

              </select>
            </div>
          </div>

          <div class="row p-2">
            <div class="col-sm-6">
              <label for="">Email</label>
              <input type="email" name="email" class="form-control form-control-sm" placeholder="" value="" autocomplete="off">
            </div>

            <div class="col-sm-6">
              <label for="">Status:</label>
              <select name="status" id="" class="form-control form-control-sm">
                <option value="1">Active</option>
                <option value="0">In-Active</option>
              </select>
            </div>

          </div>

          <div class="row p-2">
            <div class="col-sm-6">
              <label for="">Telephone:</label>
              <input type="number" name="telephone" class="form-control form-control-sm" placeholder="" value="" autocomplete="off">
            </div>

            <div class="col-sm-6">
              <label for="">Mobile:</label>
              <input type="number" name="mobile_no" class="form-control form-control-sm" placeholder="" value="" autocomplete="off">
            </div>
          </div>

          <div class="row p-2">
            <div class="col-sm-12">
              <label for="">Note:</label>
              <textarea name="note" id="" cols="30" rows="3" class="form-control form-control-sm "></textarea>
            </div>
          </div>


          <div class="row p-2">
            <div class="col-sm-6">
              <label for="">Login Required:</label>
              <input type="checkbox" name="contact_login_req" value="1" id="">
            </div>
          </div>



      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary add_contact_submit">ADD</button>
      </div>
      </form>
    </div>
  </div>
</div>



<!-- add communication -->

<div class="modal fade bd-example-modal-lg add_communications" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Communicataion</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="manage_communicatation" name="manage_communicatation" action="javascript:void(0);">
          <input type="hidden" name="comm_communications_customer_id" class="comm_communications_customer_id" value="">


          <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">

          <div class="row p-2 errors">


          </div>

          <div class="row p-2">
            <div class="col-sm-6">
              <label for="">Contact Name:</label>
              <select name="comm_communications_contact_id" class="form-control form-control-sm contact_name_dropdown">
                <option value="" selected disabled>Select contact</option>
              </select>

            </div>

            <div class="col-sm-6">
              <label for="">Subject:</label>
              <input type="text" name="subject" class="form-control form-control-sm" placeholder="" value="" autocomplete="off">

            </div>
          </div>

          <div class="row p-2">
            <div class="col-sm-12">
              <label for="">Note:</label>
              <textarea name="note" id="" cols="30" rows="3" class="form-control form-control-sm "></textarea>
            </div>
          </div>

          <div class="row p-2">
            <div class="col-sm-6">
              <label for="">Date Of Communication:</label>
              <input type="date" name="date_of_communication" class="form-control form-control-sm date_of_communication" placeholder="" value="" autocomplete="off">

            </div>

            <div class="col-sm-6">
              <label for="">Follow Up Date:</label>
              <input type="datetime-local" name="follow_up_date" class="form-control form-control-sm follow_up_date" placeholder="" value="" autocomplete="off">
            </div>
          </div>

          <div class="row p-2">


            <div class="col-sm-6">
              <label for="">Mode:</label>
              <select name="communication_mode" id="" class="form-control form-control-sm">
                <option value="" selected disabled>Select</option>
                <option value="Incoming">Incoming</option>
                <option value="Outgoing">Outgoing</option>

              </select>
            </div>


            <div class="col-sm-6">
              <label for="">Medium:</label>
              <select name="medium" id="" class="form-control form-control-sm medium_communication">
                <option value="" selected disabled>Select</option>
                <option value="Face2Face">Face2Face</option>
                <option value="Email">Email</option>
                <option value="Fax">Fax</option>
                <option value="Telephone">Telephone</option>
                <option value="Exhibition">Exhibition</option>
                <option value="Conference">Conference</option>
                <option value="Others">Others</option>

              </select>
            </div>
          </div>

          <div class="row p-2">
            <div class="col-sm-12 other_text_input">
              
            </div>
          </div>

          <div class="row p-2">
            <div class="col-sm-6">
              <label for="">Connected To:</label>
              <select name="connected_to" id="" class="form-control form-control-sm connected_to" data-id="">
                <option value="" selected disabled>Select</option>
                <option value="Lead">Lead</option>
                <option value="Opportunity">Opportunity</option>

              </select>
            </div>

            <div class="col-sm-6 connected_depend" style="display: none;">
              <label for="" class="connected_label"></label>
              <select name="" id="" class="form-control form-control-sm connected_selectBox">
              </select>
            </div>

          </div>


      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary add_communication_submit">ADD</button>
      </div>
      </form>
    </div>
  </div>
</div>


<!-- add opportunity -->
<div class="modal fade bd-example-modal-lg add_opportunity" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Opportunity</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="manage_opportunity" name="manage_opportunity" action="javascript:void(0);">
          <input type="hidden" value="" name="opportunity_customer_id" class="customer_id_op">


          <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">



          <div class="row p-2">
            <div class="col-sm-6">
              <label for="">Opportunity Name:</label>
              <input type="text" name="opportunity_name" class="form-control form-control-sm" placeholder="" value="" autocomplete="off">

            </div>

            <div class="col-sm-6">
              <label for="">Type:</label>
              <select name="types" id="" class="form-control form-control-sm">
                <option value="" selected disabled>Select</option>
                <option value="Testing">Testing</option>
                <option value="Analytical">Analytical</option>
                <option value="Operations">Operations</option>
                <option value="Calibration">Calibration</option>
                <option value="Manpower">Manpower</option>
                <option value="Materials">Materials</option>
                <option value="Inspections">Inspections</option>
                <option value="Training">Training</option>                

              </select>

            </div>
          </div>

          <div class="row p-2">
            <div class="col-sm-12">
              <label for="">Opportunity Value:</label>
              <input type="number" name="opportunity_value" class="form-control form-control-sm" placeholder="" value="" autocomplete="off">
            </div>
          </div>

          <div class="row p-2">
            <div class="col-sm-6">
              <label for="">Est. Closure Date::</label>
              <input type="date" name="estimated_closure_date" class="form-control form-control-sm" placeholder="" value="" autocomplete="off">

            </div>

            <div class="col-sm-6">
              <label for="">Description:</label>
              <textarea name="description" id="" cols="30" rows="3" class="form-control form-control-sm " minlength="20"></textarea>
            </div>
          </div>

          <div class="row p-2">
            <div class="col-sm-6">
              <label for="">Contact Name:</label>
              <select name="opportunity_contact_id" class="form-control form-control-sm contact_name_dropdown" >
                <option value="" selected disabled>Select contact</option>
              </select>
            </div>

            <div class="col-sm-6">
              <label for="">Assigned To:</label>
              <select name="op_assigned_to" id="" class="form-control form-control-sm op_assigned_to">


              </select>
            </div>

          </div>



      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary add_opportunity_submit">ADD</button>
      </div>
      </form>
    </div>
  </div>
</div>

<!-- view communication -->
<div class="modal fade bd-example-modal-lg manage_communications" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">View Communications</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="table-responsive small">
          <table id="view_communications_data" class="table table-sm table-hovered">
            <thead>
              <tr>
                <th>SL NO.</th>
                <th>Contact Name</th>
                <th>Subject</th>
                <th>Note</th>
                <th>Date of communication</th>
                <th>Medium</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>

            </tbody>
          </table>
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- view contact -->
<div class="modal fade bd-example-modal-lg view_contact" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">View Contacts</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="table-responsive small">
          <table id="view_contacts_data" class="table table-sm table-hovered">
            <thead>
              <tr>
                <th>SL NO.</th>
                <th>Contact Name</th>
                <th>Email</th>
                <th>Mobile No.</th>
                <th>Contact Type</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>

            </tbody>
          </table>
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<!-- view opportunity -->
<div class="modal fade bd-example-modal-lg manage_opportunity" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">View Opportunity</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="table-responsive small">
          <table id="view_opportunity_data" class="table table-sm table-hovered view_opportunity_data" >
            <thead>
              <tr>
                <th>SL NO.</th>
                <th>Opportunity Name</th>
                <th>Opportunity value</th>
                <th>Opportunity type</th>
                <th>Estimated closure date</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>

            </tbody>
          </table>
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- view customers modal -->
<div class="modal fade bd-example-modal-lg view_customer_dets" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">CUSTOMER DETAILS</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <div class="row">
                <div class="table-responsive">
                    <table class="table table-sm view_cust_table">
                        <tbody>
                        </tbody>
                    </table>
                </div> 
          </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- manage customers relationships -->
<div class="modal fade bd-example-modal-lg manage_customers_relations" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Manage Customers Relation</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <input type="hidden" value="" name="map_type" class="map_type">
        <input type="hidden" value="" name="" class="map_type_customer_id">
        <div class="row p-2">
          <div class="col-sm-6">
            <select name="" id="" class="manage_relationship_type form-control form-control-sm">

            </select>
          </div>
        </div>

        <div class="row p-2">
          <div class="col-sm-12">
            <input type="text" class="form-control form-control-sm search_mapp" placeholder="Search by name..." autocomplete="off">
            <br>
            <div class="table-responsive small" style='height:25vh'>
          
              <table id="" class="table table-sm table-hovered map_listing" >

              </table>
            </div>
          </div>
        </div>
        <hr>
        <div class="row p-2">
          <div class="col-sm-12">
            <input type="text" class="form-control form-control-sm search_mappped" placeholder="Search by name..." autocomplete="off">
            <br>
            <div class="table-responsive small" style='height:25vh'>
           
         
              <table id="" class="table table-sm table-hovered mapped_listing" >

              </table>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<!-- view quotations -->
<div class="modal fade" id="view_quotation" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content" style="max-height: 500px;">
        <div class="modal-header bg-primary">
          <h5 class="modal-title" id="exampleModalLabel">QUOTES LIST</h5>
          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
          <div class="modal-body">

              <div class="row">
                    <div class="col-sm-6">
                          <input type="hidden" class="customer_id_quote" value="">
                          <input type="text" class="search_quote form-control form-control-sm" placeholder="SEARCH..." name="" id="">
                    </div>
                    <div class="col-sm-4">
                        <button class="btn btn-sm btn-primary search_listing">SEARCH</button>
                        <button class="btn btn-sm btn-danger clear_listing">CLEAR</button>
                    </div>
                </div>
                <hr>
                <div class="table-responsive">
                    <table class="table table-sm">
                          <thead>
                              <tr>
                                  <th scope="col">SL No.</th>
                                  <th scope="col">Quote Reference No.</th>
                                  <th scope="col">Quote Date</th>
                                  <th scope="col">Quote Value</th>
                                  <th scope="col">Quote Status</th>
                                  <th scope="col">Created By</th>
                              </tr>
                          </thead>

                          <tbody class="quote_table">
                                
                          </tbody>
                    </table>
                </div>

                <div class="container">
                  <div class="row">
                      <div class="col-sm-4"></div>
                      <div class="col-sm-4" id="quotes_pagination"></div>
                      <div class="col-sm-4"></div>
                  </div>
               </div>
          
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
</div>
<!-- end -->

<script>
  function searchfilter() {

    var url = '<?php echo base_url("customers"); ?>';

    var customer_id = $('.customer_id').val();
    var customer_type = $('.customer_type').val();
    var accope_customer = $('.accope_customer').val();
    var search = $('#search').val();

    if (customer_id != '') {
      url = url + '/' + customer_id;
    } else {
      url = url + '/NULL';
    }
    if (customer_type != '') {
      url = url + '/' + customer_type;
    } else {
      url = url + '/NULL';
    }
    if (accope_customer != '') {
      url = url + '/' + accope_customer;
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

$(function(){
  // $('.view_quote').on('click',function(){
  //     var customer_id = $(this).data("id");
  //     get_quotes_by_customer(customer_id);
  // });

  // function  get_quotes_by_customer(customer_id){
  //   const _tokken = $('meta[name="_tokken"]').attr('value');
  //   $.post({
  //     url:"<?php echo base_url('customer_management/Customers/view_quotations')?>",
  //     data:{
  //       customer_id:customer_id,
  //       _tokken:_tokken
  //     },
  //     success:function(res){
  //       var data = $.parseJSON(res);
  //       $('.quote_table').html("");
  //       if(data){
  //         $.each(data,function(index,value){
  //             tbody = '<tr>';
  //             tbody += '<td>'+(index+1)+'</td>';
  //             tbody += '<td>'+value.reference_no+'</td>';
  //             tbody += '<td>'+value.quote_date+'</td>';
  //             tbody += '<td>'+value.quote_value+'</td>';
  //             tbody += '<td>'+value.quote_status+'</td>';
  //             tbody += '<td>'+value.created_by+'</td>';
  //             tbody += '</tr>';
  //             $('.quote_table').append(tbody);
  //         })
  //       }
  //     }
  //   })
  // }  
});

$(document).ready(function() {
    $('.country_name').focus(function(e) {
      getAutolist('country_id', 'country_name', 'country_list', 'country_li', 'status="1"', 'country_name', 'country_id as id,country_name as name', 'mst_country');

    })
    var where_array = {};
    where_array['isactive']= 'Active';
    <?php if(exist_val('Branch/Wise',$this->session->userdata('permission'))){ ?>
      <?php $checkUser = $this->session->userdata('user_data'); ?>
      where_array['mst_branch_id']='<?php echo $checkUser->branch_id; ?>'
    <?php } ?>
    $('.customer_name').focus(function(e) {
      getAutolist('customer_id', 'customer_name', 'customer_list', 'customer_li', JSON.stringify(where_array), 'customer_name', 'customer_id as id,customer_name as name', 'cust_customers');

    })

    $('.medium_communication').on('change',function(){
      if($(this).val()=="Others"){
          
          textarea = '<input type="text" class="form-control form-control-sm" name="other_medium" id="" cols="30" rows="3" placeholder="Enter other medium"></input>';
          $('.other_text_input').append(textarea);
      }
      else{
        $('.other_text_input').html("");
      }
    })



    $('.view_customers_details').on('click',function(){
      
      var customer_id = $(this).data("id");
      const _tokken = $('meta[name="_tokken"]').attr('value');

      $.ajax({
          url:"<?php echo base_url('customer_management/Customers/view_cust_details')?>",
          method:"POST",
          data:{
            customer_id:customer_id,
            _tokken:_tokken
          },
          success:function(data){
            var customer = $.parseJSON(data);

            if(customer.non_taxable=='1'){
              customer.non_taxable = "YES";
            }else{
              customer.non_taxable = "NO";
            }
            if(customer.accop_cust=='1'){
              customer.accop_cust = "MARKED";
            }else{
              customer.accop_cust = "UN-MARKED";
            }
            if(customer.customer_type){
              customer.customer_type=customer.customer_type;
            }
            else{
              customer.customer_type="-";
            }
            if(customer.customer_name){
              customer.customer_name=customer.customer_name;
            }
            else{
              customer.customer_name="-";
            }

            if(customer.email){
              customer.email=customer.email;
            }
            else{
              customer.email="-";
            }

            if(customer.telephone){
              customer.telephone=customer.telephone;
            }
            else{
              customer.telephone="-";
            }

            if(customer.mobile){
              customer.mobile=customer.mobile;
            }
            else{
              customer.mobile="-";
            }

            if(customer.address){
              customer.address=customer.address;
            }
            else{
              customer.address="-";
            }

            if(customer.city){
              customer.city=customer.city;
            }
            else{
              customer.city="-";
            }

            if(customer.po_box){
              customer.po_box=customer.po_box;
            }
            else{
              customer.po_box="-";
            }

            if(customer.country_name){
              customer.country_name=customer.country_name;
            }
            else{
              customer.country_name="-";
            }

            if(customer.state_name){
              customer.state_name=customer.state_name;
            }
            else{
              customer.state_name="-";
            }

            if(customer.location_name){
              customer.location_name=customer.location_name;
            }
            else{
              customer.location_name="-";
            }

            if(customer.web){
              customer.web=customer.web;
            }
            else{
              customer.web="-";
            }

            if(customer.credit){
              customer.credit=customer.credit;
            }
            else{
              customer.credit="-";
            }

            if(customer.pan){
              customer.pan=customer.pan;
            }
            else{
              customer.pan="-";
            }

            if(customer.tan){
              customer.tan=customer.tan;
            }
            else{
              customer.tan="-";
            }

            if(customer.gstin){
              customer.gstin=customer.gstin;
            }
            else{
              customer.gstin="-";
            }
            if(customer.retention_period){
              customer.retention_period=customer.retention_period;
            }
            else{
              customer.retention_period="-";
            }

           
            $('.view_cust_table tbody').html("");
            row = "<tr>";
            row+= "<td><b>CUSTOMER TYPE</b></td>";
            row+= "<td>"+customer.customer_type+"</td>";
            row+="</tr>";

            row+= "<tr>";
            row+= "<td><b>CUSTOMER NAME (COMPANY NAME)</b></td>";
            row+= "<td>"+customer.customer_name+"</td>";
            row+="</tr>";

            row+= "<tr>";
            row+= "<td><b>CUSTOMER EMAIL</b></td>";
            row+= "<td>"+customer.email+"</td>";
            row+="</tr>";

            row+= "<tr>";
            row+= "<td><b>TELEPHONE</b></td>";
            row+= "<td>"+customer.telephone+"</td>";
            row+="</tr>";

            row+= "<tr>";
            row+= "<td><b>MOBILE</b></td>";
            row+= "<td>"+customer.mobile+"</td>";
            row+="</tr>";

            row+= "<tr>";
            row+= "<td><b>ADDRESS</b></td>";
            row+= "<td>"+customer.address+"</td>";
            row+="</tr>";

            row+= "<tr>";
            row+= "<td><b>CITY</b></td>";
            row+= "<td>"+customer.city+"</td>";
            row+="</tr>";

            row+= "<tr>";
            row+= "<td><b>PIN CODE</b></td>";
            row+= "<td>"+customer.po_box+"</td>";
            row+="</tr>";

            row+= "<tr>";
            row+= "<td><b>COUNTRY</b></td>";
            row+= "<td>"+customer.country_name+"</td>";
            row+="</tr>";

            row+= "<tr>";
            row+= "<td><b>STATE</b></td>";
            row+= "<td>"+customer.state_name+"</td>";
            row+="</tr>";

            row+= "<tr>";
            row+= "<td><b>AREA/LOCATION</b></td>";
            row+= "<td>"+customer.location_name+"</td>";
            row+="</tr>";

            row+= "<tr>";
            row+= "<td><b>WEBSITE</b></td>";
            row+= "<td>"+customer.web+"</td>";
            row+="</tr>";

            row+= "<tr>";
            row+= "<td><b>CREDIT</b></td>";
            row+= "<td>"+customer.credit+"</td>";
            row+="</tr>";

            row+= "<tr>";
            row+= "<td><b>PAN NO.</b></td>";
            row+= "<td>"+customer.pan+"</td>";
            row+="</tr>";

            row+= "<tr>";
            row+= "<td><b>TAN NO.</b></td>";
            row+= "<td>"+customer.tan+"</td>";
            row+="</tr>";

            row+= "<tr>";
            row+= "<td><b>GST NO.</b></td>";
            row+= "<td>"+customer.gstin+"</td>";
            row+="</tr>";

            row+= "<tr>";
            row+= "<td><b>RETAIN PERIOD</b></td>";
            row+= "<td>"+customer.retention_period+"</td>";
            row+="</tr>";

            row+= "<tr>";
            row+= "<td><b>NON-TAXABLE</b></td>";
            row+= "<td>"+customer.non_taxable+"</td>";
            row+="</tr>";

            row+= "<tr>";
            row+= "<td><b>MARKED AS ACCOP</b></td>";
            row+= "<td>"+customer.accop_cust+"</td>";
            row+="</tr>";

            $('.view_cust_table tbody').append(row);
          }
      });
      // return false;

    });

    // state
    $('.state_name').focus(function(e) {

      var country_id = $('.country_id').val();
      var where_array = JSON.stringify({
        'status': '1',
        'mst_provinces_country_id': country_id
      })

      getAutolist('state_id', 'state_name', 'state_list', 'state_li', where_array, 'province_name', 'province_id as id,province_name as name', 'mst_provinces');
    })



    // frond end validation 

    function validate_contact() {
      $("form[name='manage_contact']").validate({
        rules: {
          contact_name: "required",
          email: "required",
          type: {
            required: true
          },
          submitHandler: function(form) {
            form.submit();
          }

        }
      });
    }

    $('.manage_contact').click(function() {

      var customer_id = $(this).attr('data-id');
      $('.contacts_customer_id').val(customer_id);

    })




    // post data of conatact form

    $('#manage_contact').on('submit', function(e) {
      e.preventDefault();

      $.ajax({
        url: "<?php echo base_url('customer_management/Customers/add_contact') ?>",
        method: "POST",
        data: $('#manage_contact').serialize(),
        success: function(data) {
          var msg = $.parseJSON(data);
          if (msg.status > 0) {
            $.notify(msg.msg, 'success');
            $('.add_contact').modal('hide');
            $('#manage_contact').trigger('reset');
          } else {
            $.notify(msg.msg, 'error');
          }
          if (msg.errors) {
            var error = msg.errors;
            $('.manage_contact_add').remove();
            $.each(error, function(i, v) {
              $('#manage_contact input[name="' + i + '"]').after('<span class="text-danger manage_contact_add">' + v + '</span>');
              $('#manage_contact select[name="' + i + '"]').after('<span class="text-danger manage_contact_add">' + v + '</span>');
            });

          } else {
            $('.manage_contact_add').remove();
          }
        }

      });
    })

    // load contact

    function load_contact(customer_id, _tokken) {
      $.ajax({
        url: "<?php echo base_url('customer_management/Customers/load_contacts') ?>",
        method: "post",
        data: {
          customer_id: customer_id,
          _tokken: _tokken
        },
        success: function(data) {
          $('#view_contacts_data tbody').html("");
          var data = $.parseJSON(data);
          var s_lo = 1;
          var delIcon = "<?php echo base_url('assets/images/delete.png') ?>";
          $.each(data, function(i, v) {

            var row = "";
            row += "<tr>";
            row += "<td>" + s_lo + "</td>";
            row += "<td>" + v.contact_name + "</td>";
            row += "<td>" + v.email + "</td>";
            row += "<td>" + v.mobile_no + "</td>";
            row += "<td>" + v.type + "</td>";
            row += "<td><a type='button' class='deletecontact' data-id='" + v.contact_id + "' data-value='" + v.customer_id + "'><img src='" + delIcon + "' title='Delete Contact' height='20px' width='20px'></a></td>";
            row += "</tr>";
            $('#view_contacts_data tbody').append(row);
            s_lo++;
          })
        }

      })
    }



    $('.load_contact').click(function() {
      var customer_id = $(this).attr("data-id");
      const _tokken = $('meta[name="_tokken"]').attr('value');
      load_contact(customer_id, _tokken);
    })



    $('.communication_mange').click(function() {
      var customer_id = $(this).attr("data-id");
      const _tokken = $('meta[name="_tokken"]').attr('value');
      load_communications(customer_id, _tokken);

    })

    $('.manage_opportunity_btn').click(function() {
      var customer_id = $(this).attr("data-id");
      const _tokken = $('meta[name="_tokken"]').attr('value');
      load_OPPORTUNITY(customer_id, _tokken);

    })





    // delete contact

    $(document).on('click', '.deletecontact', function() {
      if(confirm("are you sure!")){
        const _tokken = $('meta[name="_tokken"]').attr('value');
      var contact_id = $(this).attr('data-id');
      var customer_id = $(this).attr('data-value');
      $.ajax({
        url: "<?php echo base_url('customer_management/Customers/delete_contact') ?>",
        method: "post",
        data: {
          contact_id: contact_id,
          customer_id:customer_id,
          _tokken: _tokken
        },
        success: function(data) {
          var msg = $.parseJSON(data);
          if (msg.status > 0) {
            $.notify(msg.msg, 'success');
            load_contact(customer_id, _tokken);
          } else {
            $.notify(msg.msg, 'error');
          }
        }
      })
      }
      else{
        return false;
      }
    })


    // delete communications


    $(document).on('click', '.deletecommunication', function() {
      if(confirm("are you sure!")){
        const _tokken = $('meta[name="_tokken"]').attr('value');
      var communication_id = $(this).attr('data-id');
      var customer_id = $(this).attr('data-value');
      $.ajax({
        url: "<?php echo base_url('customer_management/Customers/delete_communications') ?>",
        method: "post",
        data: {
          communication_id: communication_id,
          customer_id:customer_id,
          _tokken: _tokken
        },
        success: function(data) {
          var msg = $.parseJSON(data);
          if (msg.status > 0) {
            $.notify(msg.msg, 'success');
            load_communications(customer_id, _tokken);
          } else {
            $.notify(msg.msg, 'error');
          }
        }
      })
      }else{
        return false;
      }
      
    })


    // delete opportunity

    
    
    $(document).on('click', '.deleteopportunity', function() {
      if(confirm("are you sure!")){
        const _tokken = $('meta[name="_tokken"]').attr('value');
      var opportunity_id = $(this).attr('data-id');
      var customer_id = $(this).attr('data-value');
      $.ajax({
        url: "<?php echo base_url('customer_management/Customers/delete_opportunity') ?>",
        method: "post",
        data: {
          opportunity_id: opportunity_id,
          customer_id:customer_id,
          _tokken: _tokken
        },
        success: function(data) {
          var msg = $.parseJSON(data);
          if (msg.status > 0) {
            $.notify(msg.msg, 'success');
            load_OPPORTUNITY(customer_id, _tokken);

          } else {
            $.notify(msg.msg, 'error');
          }
        }
      })
      }else{
        return false;
      }
    })

    // contact dropdown

    $('.communication_btn').click(function() {
      var customer_id = $(this).attr("data-id");
      $('.comm_communications_customer_id').val(customer_id);
      contact_dropdown(customer_id);
    })

    $('.add_opportunity_new').click(function() {
      var customer_id = $(this).attr("data-id");
      $('.customer_id_op').val(customer_id);
      const _tokken = $('meta[name="_tokken"]').attr('value');
      contact_dropdown(customer_id);
      load_op_assigned_to(

      );
    })

    function contact_dropdown(customer_id) {
      const _tokken = $('meta[name="_tokken"]').attr('value');
      $.ajax({
        url: "<?php echo base_url('customer_management/Customers/load_contacts') ?>",
        method: "post",
        data: {
          customer_id: customer_id,
          _tokken: _tokken
        },
        success: function(data) {

          var data = $.parseJSON(data);
          $('.contact_name_dropdown').html("");
          var option = "";
            option += "<option value='' selected disabled>Select contact</option>";
          $('.contact_name_droopdown').append(option);
          if (data) {
            $.each(data, function(i, v) {
              var option = "";
              option += "<option value='" + v.contact_id + "'>" + v.contact_name + "</option>";
              $('.contact_name_dropdown').append(option);
            })
          } else {
            var option = "";
            option += "<option value='' selected disabled>Select contact</option>";
            option += "<option value=''>No Record Found</option>";
            $('.contact_name_dropdown').append(option);
          }
        }
      })

    }

    // connected to dropdown

    $('.connected_to').on('change', function() {
      var connected_to = $(this).val();
      var customer_id = $('.comm_communications_customer_id').val();
      switch (connected_to) {
        case 'None':
          $('.connected_depend').html("");
          break;
        case 'Quote':
          $('.connected_depend').css("display", "inline-block");
          $('.connected_depend').html("");
          var selectBox = "<label>Reference No.</label>";
          selectBox += "<select name='reference_number' class='form-control form-control-sm reference_number'>";
          selectBox += "<option value=''>Select Reference No.</option>"
          selectBox += "</select>";
          $('.connected_depend').html(selectBox);
          load_reference(customer_id)
          break;
        case 'Opportunity':
          $('.connected_depend').css("display", "inline-block");
          $('.connected_depend').html("");
          var selectBox = "<label>Opportunity.</label>";
          selectBox += "<select name='comm_communications_opportunity_id' class='form-control form-control-sm oportunity '>";
          selectBox += "<option value=''>Select Opportunity</option>"
          selectBox += "</select>";
          $('.connected_depend').html(selectBox);
          load_oportunity(customer_id)

          break;
      }

    })


    // quote ajax

    function load_reference(customer_id) {
      const _tokken = $('meta[name="_tokken"]').attr('value');
      $.ajax({
        url: "<?php echo base_url('customer_management/Customers/load_reference_no') ?>",
        method: "post",
        data: {
          customer_id: customer_id,
          _tokken: _tokken
        },
        success: function(data) {
          var data = $.parseJSON(data);
          $('.reference_number').html("");
          if (data) {
            $.each(data, function(index, value) {
              $('.reference_number').append($('<option value="">Select Reference No.</option><option value="' + value.reference_id + '">' + value.reference + '</option>'));
            })
          } else {
            $('.reference_number').append($('<option value="">Select Reference No.</option><option value="">NO RECORD FOUND</option>'));
          }
        }
      })
    }

    // oportunity ajax

    function load_oportunity(customer_id) {
      const _tokken = $('meta[name="_tokken"]').attr('value');
      $.ajax({
        url: "<?php echo base_url('customer_management/Customers/load_oportunity') ?>",
        method: "post",
        data: {
          customer_id: customer_id,
          _tokken: _tokken
        },
        success: function(data) {
          var data = $.parseJSON(data);
          $('.oportunity').html("");
          if (data) {
            $.each(data, function(index, value) {
              $('.oportunity').append($('<option value="">Select Opportunity</option><option value="' + value.reference_id + '">' + value.reference + '</option>'));
            })
          } else {
            $('.oportunity').append($('<option value="">Select Opportunity</option><option value="">NO RECORD FOUND</option>'));
          }
        }
      })
    }


  //  disable past date 
  $(function(){
    var dtToday = new Date();
    
    var month = dtToday.getMonth() + 1;
    var day = dtToday.getDate();
    var year = dtToday.getFullYear();
    var hour = dtToday.getHours();
    var minutes = dtToday.getMinutes();
    if(month < 10)
        month = '0' + month.toString();
    if(day < 10)
        day = '0' + day.toString();
    
    var maxDate = year + '-' + month + '-' + day + 'T' + hour + ':' + minutes;


    $('.follow_up_date').attr('min', maxDate);

    
});


  // code end 
    // insert communication

    $('#manage_communicatation').on('submit', function(e) {
      e.preventDefault();

      $.ajax({
        url: "<?php echo base_url('customer_management/Customers/add_communication') ?>",
        method: "POST",
        data: $('#manage_communicatation').serialize(),
        success: function(data) {
          var msg = $.parseJSON(data);
          if (msg.status > 0) {
            $.notify(msg.msg, 'success');
            $('.add_communications').modal('hide');
            $('#manage_communicatation').trigger('reset');
          } else {
            $.notify(msg.msg, 'error');
          }
          if (msg.errors) {
            var error = msg.errors;
            $('.manage_communicatation_add').remove();
            $.each(error, function(i, v) {
              $('#manage_communicatation input[name="' + i + '"]').after('<span class="text-danger manage_communicatation_add">' + v + '</span>');
              $('#manage_communicatation select[name="' + i + '"]').after('<span class="text-danger manage_communicatation_add">' + v + '</span>');
            });
          } else {
            $('.manage_communicatation_add').remove();
          }

        }



      });
    })


    // LOAD COMMUNICATIONS

    function load_communications(customer_id, _tokken) {
      $.ajax({
        url: "<?php echo base_url('customer_management/Customers/load_communications') ?>",
        method: "post",
        data: {
          customer_id: customer_id,
          _tokken: _tokken
        },
        success: function(data) {
          $('#view_communications_data tbody').html("");
          var data = $.parseJSON(data);
          var s_lo = 1;
          var delIcon = "<?php echo base_url('assets/images/delete.png') ?>";
          $.each(data, function(i, v) {

            var row = "";
            row += "<tr>";
            row += "<td>" + s_lo + "</td>";
            row += "<td>" + v.contact_name + "</td>";
            row += "<td>" + v.subject + "</td>";
            row += "<td>" + v.note + "</td>";
            row += "<td>" + v.date_of_communication + "</td>";
            row += "<td>" + v.medium + "</td>";
            row += "<td><a type='button' class='deletecommunication' data-id='" + v.communication_id + "' data-value='" + customer_id + "'><img src='" + delIcon + "' title='Delete Contact' height='20px' width='20px'></a></td>";
            row += "</tr>";
            $('#view_communications_data tbody').append(row);
            s_lo++;
          })
        }

      })
    }

    // LOAD OPPORTUNITY TABLE
    function load_OPPORTUNITY(customer_id, _tokken) {
      $.ajax({
        url: "<?php echo base_url('customer_management/Customers/load_oportunity') ?>",
        method: "post",
        data: {
          customer_id: customer_id,
          _tokken: _tokken
        },
        success: function(data) {
          $('#view_opportunity_data tbody').html("");
          var data = $.parseJSON(data);
          var s_lo = 1;
          var delIcon = "<?php echo base_url('assets/images/delete.png') ?>";
          $.each(data, function(i, v) {

            var row = "";
            row += "<tr>";
            row += "<td>" + s_lo + "</td>";
            row += "<td>" + v.opportunity_name + "</td>";
            row += "<td>" + v.opportunity_value + "</td>";
            row += "<td>" + v.types + "</td>";
            row += "<td>" + v.estimated_closure_date + "</td>";
            row += "<td><a type='button' class='deleteopportunity' data-id='" + v.reference_id + "' data-value='" + customer_id + "'><img src='" + delIcon + "' title='Delete Opportunity' height='20px' width='20px'></a></td>";
            row += "</tr>";
            $('#view_opportunity_data tbody').append(row);
            s_lo++;
          })
        }

      })
    }




    // post data of opportunity
    $('#manage_opportunity').on('submit', function(e) {
      e.preventDefault();

      $.ajax({
        url: "<?php echo base_url('customer_management/Customers/add_opportunity') ?>",
        method: "POST",
        data: $('#manage_opportunity').serialize(),
        success: function(data) {
          var msg = $.parseJSON(data);
          if (msg.status > 0) {
            $.notify(msg.msg, 'success');
            $('.add_opportunity').modal('hide');
            $('#manage_opportunity').trigger('reset')
          } else {
            $.notify(msg.msg, 'error');
          }
          if (msg.errors) {
            var error = msg.errors;
            $('.add_opportunity_btn').remove();
            $.each(error, function(i, v) {
              $('#manage_opportunity input[name="' + i + '"]').after('<span class="text-danger add_opportunity_btn">' + v + '</span>');
              $('#manage_opportunity select[name="' + i + '"]').after('<span class="text-danger add_opportunity_btn">' + v + '</span>');
              $('#manage_opportunity textarea[name="' + i + '"]').after('<span class="text-danger add_opportunity_btn">' + v + '</span>');
            });

          } else {
            $('.add_opportunity_btn').remove();
          }
        }

      });
    })



    //  manage customers relationship

    $('.manage_customers_relation').click(function() {
      var customer_type = $(this).attr('data-type');
      var customer_id = $(this).attr('data-id');
      $('.map_type_customer_id').val(customer_id);
      $('.map_type').val(customer_type);
      $('.map_listing').html("");
      manage_relationship_dropdown(customer_type);
    })

    function manage_relationship_dropdown(customer_type) {
      const _tokken = $('meta[name="_tokken"]').attr('value');
      $.ajax({
        url: "<?php echo base_url('customer_management/Customers/manage_relationship_type') ?>",
        method: "post",
        data: {
          customer_type: customer_type,
          _tokken: _tokken
        },
        success: function(data) {
          var data = $.parseJSON(data);
          $('.manage_relationship_type').html("");
          if (data) {
            var option = "";
            option = "<option value='' selected disabled>Select customer type</option>";
            $('.manage_relationship_type').append(option);
            $.each(data, function(index, value) {
              option = "<option value='" + value.customer_type + "'>" + value.customer_type + "</option>";
              $('.manage_relationship_type').append(option);
            })
          }
        }
      })
    }


    $('.manage_customers_relation').on('click',function(){
      $('map_listing').html("");
      $('.mapped_listing').html("");
    })

    $(document).on('change', '.manage_relationship_type', function() {
      var type = $(this).val();
      var customer_id = $('.map_type_customer_id').val();
      var map_type = $('.map_type').val();
      get_map_customers(customer_id, map_type, type,null);
      get_mapped_customers(customer_id, map_type, type,null);

    })

    $('.search_mapp').on('keyup',function(){
      var type = $('.manage_relationship_type').val();
      var customer_id = $('.map_type_customer_id').val();
      var map_type = $('.map_type').val();
      var search = $(this).val();

      get_map_customers(customer_id, map_type,type,search);
    })

    $('.search_mappped').on('keyup',function(){
      var type = $('.manage_relationship_type').val();
      var customer_id = $('.map_type_customer_id').val();
      var map_type = $('.map_type').val();
      var search = $(this).val();
      get_mapped_customers(customer_id, map_type, type,search);
    })


    function get_map_customers(customer_id, map_type,type,search) {
      const _tokken = $('meta[name="_tokken"]').attr('value');
      $.ajax({
        url: "<?php echo base_url('customer_management/Customers/map_listing') ?>",
        method: "post",
        data: {
          type: type,
          customer_id: customer_id,
          map_type: map_type,
          search:search,
          _tokken: _tokken
        },
        success: function(data) {
          var data = $.parseJSON(data);
          if (data) {
            $('.map_listing').html("");
            var addbtn = "<?php echo base_url("assets/images/add.png") ?>";
            var thead = "<thead>";
            thead += "<tr>";
            thead += "<th>S No.</th>";
            thead += "<th>" + type + " " + "Name" + "</th>";
            thead += "<th>" + type + " " + "Code" + "</th>";
            thead += "<th>" + type + " " + "City" + "</th>";
            thead += "<th>Action</th>";
            thead += "</tr>";
            thead += "</thead>";
            var tbody = "<tbody>";
            tbody+="</tbody>";
            $('.map_listing').append(thead);
            $('.map_listing').append(tbody);
            $.each(data, function(index, value) {
              var tbody = "<tr>";
              tbody += "<td>" + (index + 1) + "</td>";
              tbody += "<td>" + value.customer_name + "</td>";
              tbody += "<td>" + value.customer_code + "</td>";
              tbody += "<td>" + value.city + "</td>";
              tbody += "<td><a style='cursor:pointer' class='map_customers' data-id='" + value.customer_id + "'><img src='" + addbtn + "' title='ADD'></a></td>";
              tbody += "</tr>";
              $('.map_listing').append(tbody);
            })
          } else {
            $('.map_listing').html("");

          }
        }
      })
    }

    function get_mapped_customers(customer_id, map_type, type,search) {
      const _tokken = $('meta[name="_tokken"]').attr('value');
      $.ajax({
        url: "<?php echo base_url('customer_management/Customers/mapped_listing') ?>",
        method: "post",
        data: {
          type: type,
          customer_id: customer_id,
          map_type: map_type,
          search:search,
          _tokken: _tokken
        },
        success: function(data) {
          var data = $.parseJSON(data);
          if (data) {
            $('.mapped_listing').html("");
            var removebtn = "<?php echo base_url("assets/images/delete.png") ?>";
            var thead = "<thead>";
            thead += "<tr>";
            thead += "<th>S No.</th>";
            thead += "<th>" + type + " " + "Name" + "</th>";
            thead += "<th>" + type + " " + "Code" + "</th>";
            thead += "<th>" + type + " " + "City" + "</th>";
            thead += "<th>Action</th>";
            thead += "</tr>";
            thead += "</thead>";
            var tbody = "<tbody>";
            tbody+="</tbody>";
            $('.mapped_listing').append(thead);
            $('.mapped_listing').append(tbody);
            $.each(data, function(index, value) {
              var tbody = "<tr>";
              tbody += "<td>" + (index + 1) + "</td>";
              tbody += "<td>" + value.customer_name + "</td>";
              tbody += "<td>" + value.customer_code + "</td>";
              tbody += "<td>" + value.city + "</td>";
              tbody += "<td><a style='cursor:pointer' class='mapped_customers_remove' data-id='" + value.customer_id + "'><img src='" + removebtn + "' title='REMOVE'></a></td>";
              tbody += "</tr>";
              $('.mapped_listing').append(tbody);
            })
          } else {
            $('.mapped_listing').html("");

          }
        }
      })
    }

    $(document).on('click', '.map_customers', function() {
      var customer_id = $('.map_type_customer_id').val();
      var map_id = $(this).attr('data-id');
      var type = $('.manage_relationship_type').val();
      var map_type = $('.map_type').val();
      const _tokken = $('meta[name="_tokken"]').attr('value');

      $.ajax({
        url: "<?php echo base_url('customer_management/Customers/map_customers') ?>",
        method: "post",
        data: {
          customer_id: customer_id,
          map_id: map_id,
          type: type,
          map_type: map_type,
          _tokken: _tokken
        },
        success: function(data) {
          var data = $.parseJSON(data);
          if (data.status > 0) {
            $.notify(data.msg, 'success');
            var customer_id = $('.map_type_customer_id').val();
            get_map_customers(customer_id, map_type, type,null);
            get_mapped_customers(customer_id, map_type, type,null);
          } else {
            $.notify(data.msg, 'error');
          }
        }

      })
    })


    


    $(document).on('click', '.mapped_customers_remove', function() {
      if(confirm("Are you sure!")){
        const _tokken = $('meta[name="_tokken"]').attr('value');
      var customer_id = $('.map_type_customer_id').val();
      var un_map_id = $(this).attr('data-id');
      var type = $('.manage_relationship_type').val();
      var map_type = $('.map_type').val();

      $.ajax({
        url: "<?php echo base_url('customer_management/Customers/remove_mapped_customers') ?>",
        method: "post",
        data: {
          customer_id: customer_id,
          un_map_id: un_map_id,
          type: type,
          map_type: map_type,
          _tokken: _tokken
        },
        success: function(data) {
          var data = $.parseJSON(data);
          if (data.status > 0) {
            $.notify(data.msg, 'success');
            var customer_id = $('.map_type_customer_id').val();
            get_map_customers(customer_id, map_type, type,null);
            get_mapped_customers(customer_id, map_type, type,null);
          } else {
            $.notify(data.msg, 'error');
          }
        }

      })
      }
      else{
        return false;
      }
    })


    function load_op_assigned_to() {

      $.ajax({
        url: "<?php echo base_url('customer_management/Customers/load_assign_to') ?>",
        method: "GET",
        success: function(data) {
          var data = $.parseJSON(data);
          $('.op_assigned_to').html("");
          if (data) {
            var option = "";
            option = "<option value='' selected disabled>Select</option>";
            $('.op_assigned_to').append(option);
            $.each(data, function(index, value) {
              option = "<option value='" + value.user_id + "'>" + value.user_name + "</option>";
              $('.op_assigned_to').append(option);
            })
          }
        }
      })
    }

  })


  var css = {
    "position": "absolute",
    "width": "95%",
    "font-size": "12px",
    "z-index": 999,
    "overflow-y": "auto",
    "overflow-x": "hidden",
    "max-height": "200px",
    "cursor": "pointer",
};

  function getAutolist(hide_input, input, ul, li, where, like, select, table) {

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
      var _URL = base_url + "get_auto_list";
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
              ulEvent.append(
                $(
                  '<li class="list-group-item ' +
                  li +
                  '"' +
                  "data-id=" +
                  value.id +
                  ">" +
                  value.name +
                  "</li>"
                )
              );
            });
          } else {
            ulEvent.append(
              $(
                '<li class="list-group-item ' +
                li +
                '"' +
                'data-id="not">NO REORD FOUND</li>'
              )
            );
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
</script>
<!-- Modal to show log -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content" style="max-height: 500px;">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">CUSTOMER LOG</h5>
          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <table class="table">
            <thead>
              <tr>
                <th>SL.No.</th>
                <th>Operation</th>
                <th>Action</th>
                <th>Performed By</th>
                <th>Performed at</th>
              </tr>
            </thead>
            <tbody id="customer_log"></tbody>
          </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
</div>

  <!-- added by saurabh on 23-03-2021 -->
  <script>
    $(document).ready(function() {
      const url = $('body').data('url');
      const _tokken = $('meta[name="_tokken"]').attr('value');
      // Ajax call to get log
      $('.log_view').click(function() {
        $('#customer_log').empty();
        var customer_id = $(this).data('id');
        $.ajax({
          type: 'post',
          url: url + 'customer_management/customers/get_customer_log',
          data: {
            _tokken: _tokken,
            customer_id: customer_id
          },
          success: function(data) {
            var data = $.parseJSON(data);
            var value = '';
            sno = Number();
            $.each(data, function(i, v) {
              sno += 1;
              var operation = v.action_taken;
              var action_message = v.text;
              var taken_by = v.taken_by;
              var taken_at = v.taken_at;
              value += '<tr>';
              value += '<td>' + sno + '</td>';
              value += '<td>' + operation + '</td>';
              value += '<td>' + action_message + '</td>';
              value += '<td>' + taken_by + '</td>';
              value += '<td>' + taken_at + '</td>';
              value += '</tr>';

            });
            $('#customer_log').append(value);
          }
        });
      });
      // ajax call to get log ends here
    });
  </script>
  <!-- added by saurabh on 23-03-2021 -->


<script>
$(document).ready(function(){
  var base_url = $("body").attr("data-url");
  var _tokken = $('meta[name="_tokken"]').attr("value");

  $('.view_quote').on('click',function(){
      var customer_id = $(this).data("id");
      $('.customer_id_quote').attr("value",customer_id);
      var pageno = 0;
     loadPagination(pageno,customer_id);
  });
 
  $("#quotes_pagination").on("click", "a", function (e) {
    e.preventDefault();
    customer_id = $('.customer_id_quote').val();
    pageno = $(this).attr("data-ci-pagination-page");
    loadPagination(pageno,customer_id);
  });

  function loadPagination(pagno,customer_id) {
    var search = $(".search_quote").val() ? btoa($(".search_quote").val()) : "NULL";
    $.ajax({
      url: base_url + "customer_management/Customers/view_quotations/" + search + "/" + pagno + "/" + customer_id,
      type: "get",
      dataType: "json",
      success: function (response) {
        $("#quotes_pagination").html(response.links);
        createTable(response.result);
      },
    });
  }
 

    function createTable(result) {
      $(".quote_table").empty();
      $(".quote_table").html(result);
    }
    $(document).on("click", ".search_listing", function () {
      customer_id = $('.customer_id_quote').val();
      loadPagination(0,customer_id);
    });
    
    $(document).on("click", ".clear_listing", function () {
      $(".search_quote").val("");
      customer_id = $('.customer_id_quote').val();
      loadPagination(0,customer_id);
    });
})

</script>
