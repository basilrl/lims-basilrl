<script src="<?= base_url('assets/js/sample_tracking.js') ?>"></script>

<style>

.sample_details_div{
    background-color: white;
    margin: 0 auto;
    width: 100%;
    color: black;
    /* border: 2px solid green; */
}
.eclipse{
    background: green;
    border-radius: 50%;
    width: 200px;
    height: 100px;
    position: relative;
    color: white;
    margin-left: 100px;
    margin-top: 50px;
}
.eclipse .eclipse_content{
   
    margin: 0;
    position: absolute;
    padding: 30px;
    top: 50%;
    left: 50%;
    width: 100%;
    -ms-transform: translate(-50%, -50%);
    transform: translate(-50%, -50%);
    font-size: smaller;
}
.eclipse_content::after{
    -ms-transform: translate(0, -50%);
    transform: translate(0, -50%);
    content: '';
    background-color: green;
    position: absolute;
    left: 100%;
    top: 50%;
    width: 100px;
    height: 5px;
}
.eclipse_content_without_after{
    margin: 0;
    position: absolute;
    padding: 30px;
    top: 50%;
    left: 50%;
    width: 100%;
    -ms-transform: translate(-50%, -50%);
    transform: translate(-50%, -50%);
    font-size: smaller;
}


.animation1,.animation2{
  white-space: nowrap;
  overflow: hidden;    
  font-family: 'Source Code Pro', monospace;  
  font-size: 50px;
  color: #34b4eb;
}

/* Animation */
.animation1 {
  animation: animated-text 2s steps(30,end) 1s 1 normal both;
}
.animation2 {
  animation: animated-text2 2s steps(30,end) 3s 1 normal both;
}

/* text animation */

@keyframes animated-text{
  from{width: 0;}
  to{width: 800px;}
}
@keyframes animated-text2{
  from{width: 0;}
  to{width: 800px;}
}


</style>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <h1 style="text-align: center;background-color:blue;" class="bg-info">SAMPLE TRACKING SYSTEM</h1>


            <div class="row" style="margin:20px auto;">
                 <div class="col-sm-4">
                    
                    </div>
                <div class="col-sm-4">
              
                    <input type="hidden" class="gc_number_value" value="">
                    <input type="text" class="gc_number_text form-control form-control-md" placeholder="Type BASIL REPORT Number and select from list..." autocomplete="off" style="font-size:18px">
                    <ul class="list-group-item gc_tack_list" style="display:none;">
                    </ul>
    
                </div>
                <div class="col-sm-4 text-left">
                    <button class="btn btn-md btn-default search" type="button" title="Search"><i class="fas fa-search"></i></button>
                    <button class="btn btn-md btn-default refresh" type="button" title="Reset"><i class="fas fa-sync"></i></button>
                </div>

            </div>

            <div class="card">
                 <div class="sample_details_div container-fluid">
                  
                  </div>

                  <div class="row sample_tracking_div">
                        
                  <div class="col-sm-4">
                  <img src="<?php echo base_url("assets/images/gif-for-homepage.gif")?>" alt="">
                  </div>
                        <div class="col-sm-8">
                        <p class="animation1"><b>HERE YOU CAN TRACK YOUR SAMPLE</b></p>
                        <p class="animation2"><b>BY PROVIDING BASIL REPORT NUMBER</b></p>
                        </div>
                      
                  </div>
            </div>
        </div>
    </section>

</div>