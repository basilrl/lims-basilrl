<script src="<?php echo base_url(); ?>public/js/order.js"></script>
<main class="main">
    <div class="container text-center"><br /><br />
        <h2><i class="fa fa-bars"></i> ORDER'S LIST</h2>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-3">
           
            </div>
            <div class="col-sm-9">
                <div class="row">
                    <div class="col-sm-6">
                        <input type="text" class="search form-control form-control-sm" placeholder="SEARCH" name="" id="">
                    </div>
                    <div class="col-sm-6">
                        <button class="btn btn-sm btn-primary search_listing">SEARCH</button>
                        <button class="btn btn-sm btn-danger clear_listing">CLEAR</button>
                    </div>
                </div>
            </div>
        
                  
        </div>

        <div class="row">
            <div class="col-sm-12">
            <div class="input-group input-group-sm">
                     <a href="<?php echo base_url('mail_configuration/index'); ?>" class="btn btn-primary">Email Configuration</a>
                  </div>
                  <div class="mt-4 table-responsive small">
        <table class="table table-sm">
            <thead>
                <tr>
                    <th scope="col">SL NO</th>
                    <th>CUSTOMER</th>
                    <th>CONTACT NAME</th>
                    <th>EMAIL</th>
                    <th>PHONE</th>
                    <th>TXN ID</th>
                    <th>TIME</th>
                    <th>ACTION</th>
                </tr>
            </thead>
            <tbody id="order_list"></tbody>
        </table>
    </div>
              </div>

        </div>

    </div>
    
    <div class="container">
        <div class="row">
            <div class="col-sm-4"></div>
            <div class="col-sm-4" id="order_pagination"></div>
            <div class="col-sm-4"></div>
        </div>
    </div>
</main>
