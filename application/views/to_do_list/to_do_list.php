<script src="<?php echo base_url(); ?>public/js/to_do_list.js"></script>
<main class="main">
    <div class="container text-center"><br /><br />
        <h2><i class="fa fa-bars"></i>TO DO LIST</h2>
    </div>
    <div class="container-fluid">
        <div class="row">
           

            <div class="col-sm-3">
                    <select name="" id="customer_id" class="form-control form-control-sm">
                        <option value="">SELECT CUSTOMER</option>
                    </select>
            </div>
            <div class="col-sm-9">
                <div class="row">
                    <div class="col-sm-8">
                        <input type="text" class="search form-control form-control-sm" placeholder="SEARCH..." name="" id="">
                    </div>
                    <div class="col-sm-4">
                        <button class="btn btn-sm btn-primary search_listing">SEARCH</button>
                        <button class="btn btn-sm btn-danger clear_listing">CLEAR</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="mt-4 table-responsive small">
        <table class="table table-sm">
            <thead>
                <tr>
                    <th scope="col">SL NO</th>
                    <th>SUBJECT</th>
                    <th>CUSTOMER NAME</th>
                    <th>FOLLOW UP DATE</th>
                   <?php if(exist_val('To_do_list/insert_new_communication',$this->session->userdata('permission')) && exist_val('To_do_list/insert_new_opportunity',$this->session->userdata('permission'))){ ?>
                    <th>ACTION</th>
                    <?php }?>
                </tr>
            </thead>
            <tbody id="to_do_list"></tbody>
        </table>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-sm-4" id="records"></div>
            <div class="col-sm-4" id="pagination"></div>
            <div class="col-sm-4"></div>
        </div>
    </div>
</main>

<div class="modal fade" id="add_communication" tabindex="-1" role="dialog" aria-labelledby="comm_dataLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-lg" style="text-align: right;margin:0 auto">
            <div class="modal-header">
                <h5 class="modal-title" id="comm_dataLabel">Add Communication Details</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="" id="add_new_communications" action="javscript:void(0);">
                <div class="modal-body">
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                <input type="hidden" name="communication_id" class="communication_id">
                    <div class="form-group text-left">
                        <div class="row">
                            <div class="col-sm-6">
                                <label for="customer_type" class="text-dark font-weight-bold"> Select Customer Type </label>
                                <select name="customer_type" class="form-control form-control-sm customer_type" readonly disabled>
                                    <option value="">Select Customer Type</option>
                                    <option value="Factory">Factory</option>
                                    <option value="Buyer">Buyer</option>
                                    <option value="Agent">Agent</option>
                                    <option value="Thirdparty">Thirdparty</option>
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label for="comm_communications_customer_id">Customer Name:</label>
                                <select name="comm_communications_customer_id" class="form-control form-control-sm validate comm_communications_customer_id" readonly disabled>
                                    <option class="text-dark" value="">Select Customer Name</option>
                                </select>            
                            </div>
                            <div class="col-sm-6">
                                <label for="communication_mode">Mode:</label>
                                <select name="communication_mode" class="form-control form-control-sm validate communication_mode">
                                    <option value="" selected disabled>Select</option>
                                    <option value="Outgoing">Outgoing</option>
                                    <option value="Incoming">Incoming</option>
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label for="comm_communications_contact_id">Contact:</label>
                                <select name="comm_communications_contact_id" class="form-control form-control-sm validate comm_communications_contact_id">
                                    <option class="text-dark" value="">Select Contact</option>
                                </select>            
                            </div>
                            <div class="col-sm-6">
                                <label for="date_of_communication">Date of Communication:</label>
                                <input type="date" name="date_of_communication" class="form-control form-control-sm date_of_communication" value="">
                            </div>
                            <div class="col-sm-6">
                                <label for="follow_up_date">Follow Up Date:</label>
                                <input type="date" name="follow_up_date" class="form-control form-control-sm follow_up_date" value="">
                            </div>
                            <div class="col-sm-6">
                                <label for="medium" class="text-dark font-weight-bold"> Select Communication Medium </label>
                                <select name="medium" class="form-control form-control-sm medium">
                                    <option value="">Select Communication Medium</option>
                                </select>
                            </div>
                            <div class="col-sm-12 other_med" style="display: none">
                                <label for="other_medium">Others Medium:</label>
                                <input type="text" name="other_medium" class="form-control form-control-sm others_medium" value="">       
                            </div>
                            <div class="col-sm-6">
                                <label for="connected_to">Connected To:</label>
                                <select name="connected_to" class="form-control form-control-sm validate connected_to">
                                    <option value="" selected disabled>Select</option>
                                    <option value="Lead">Lead</option>
                                    <option value="Opportunity">Opportunity</option>
                                </select>
                            </div>
                            <div class="col-sm-12">
                                <label for="subject">Subject:</label>
                                <input type="text" class="form-control form-control-sm subject" name="subject" value="" placeholder="Enter Subject" readonly disabled>
                                <label for="note">Note:</label>
                                <textarea name="note" cols="30" rows="3" class="form-control form-control-sm note"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- <div class="modal fade" id="add_opportunity" tabindex="-1" role="dialog" aria-labelledby="opp_dataLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-lg" style="margin: 0 auto;">
            <div class="modal-header">
                <h5 class="modal-title" id="opp_dataLabel">Add Opportunity Details</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" id="add_new_opportunity" action="javscript:void(0);">
                <div class="modal-body">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <input type="hidden" name="opportunity_id" class="opportunity_id">
                    <div class="form-group text-left">
                        <div class="row">
                            <div class="col-sm-6">
                                <label for="opportunity_customer_type" class="text-dark font-weight-bold"> Select Customer Type </label>
                                <select name="opportunity_customer_type" class="form-control form-control-sm opportunity_customer_type" readonly disabled>
                                    <option value="">Select Customer Type</option>
                                    <option value="Factory">Factory</option>
                                    <option value="Buyer">Buyer</option>
                                    <option value="Agent">Agent</option>
                                    <option value="Thirdparty">Thirdparty</option>
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label for="cust_name">Customer Name:</label>
                                <select name="opportunity_customer_id" class="form-control form-control-sm validate opportunity_customer_id" readonly disabled>
                                    <option class="text-dark" value="">Select Customer Name</option>
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label for="opportunity_name">Opportunity Name:</label>
                                <input type="text" name="opportunity_name" class="form-control form-control-sm opportunity_name" placeholder="Enter Opportunity Name" value="" readonly disabled>
                            </div>
                            <div class="col-sm-6">
                                <label for="types">Type:</label>
                                <select name="types" class="form-control form-control-sm validate types">
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
                            <div class="col-sm-6">
                                <label for="opportunity_value">Opportunity Value:</label>
                                <input type="text" name="opportunity_value" class="form-control form-control-sm opportunity_value" placeholder="Enter Opportunity Value" value="">
                            </div>
                            <div class="col-sm-6">
                                <label for="opp_currency">Currency:</label>
                                <select name="currency_id" class="form-control form-control-sm opp_currency">
                                    <option value="">Select Currency</option>
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label for="estimated_closure_date">Estd. Closure Date:</label>
                                <input type="date" name="estimated_closure_date" class="form-control form-control-sm estimated_closure_date" value="">
                            </div>
                            <div class="col-sm-6">
                                <label for="opportunity_contact_id">Contact:</label>
                                <select name="opportunity_contact_id" class="form-control form-control-sm validate opportunity_contact_id">
                                    <option class="text-dark" value="">Select Contact</option>
                                </select>
                            </div>
                            <div class="col-sm-12">
                                <label for="">Assigned To:</label>
                                <select name="op_assigned_to" id="" class="form-control form-control-sm op_assigned_to">
                                    <option class="text-dark" value="">Select Assigned To</option>
                                </select>
                                <label for="description">Description:</label>
                                <textarea name="description" cols="100" rows="5" class="form-control form-control-sm description"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary"> Save</button>
                </div>
            </form>
        </div>
    </div>
</div> -->