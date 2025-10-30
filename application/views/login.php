<!doctype html>
<html class="no-js" lang="">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Basil LIMS</title>
    <link rel="shortcut icon" type="image/x-icon" href="public/img/logo/logp.png" /> 
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="apple-touch-icon" href="apple-touch-icon.png">
    <!-- Bootstrap CSS-->
    <link rel="stylesheet" href="<?php echo base_url('assets/dist/css/bootstrap/css/bootstrap.min.css') ?>">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="<?php echo base_url('assets/plugins/fontawesome-free/css/all.min.css') ?> ">
    <!-- Custom CSS -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/custom/style.css') ?>">
    <!-- jQuery -->
    <script src="<?php echo base_url('assets/plugins/jquery/jquery.min.js'); ?>"></script>
    <!-- Notifier -->
    <script src="<?php echo base_url('assets/js/notify.min.js'); ?>"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body >
    <div class="container">
              <div class="row justify-content-center mt-5">
            <div class="col-lg-4 col-md-6 col-sm-6">
          <div class="card shadow">
                    <?php
                    $arr = $this->session->flashdata();
                    if (!empty($arr['flash_message'])) {
                        $html = '<div class=" container flash-message text-danger"><h5>';
                        $html .= $arr['flash_message'];
                        $html .= '</div></h5>';
                        echo $html;
                    }
                    ?>
                    
                    <div class="text-center p-4">
                        <img class="w-50 mt-3 mb-3" src="<?php echo base_url(); ?>assets/images/logo-login.png" alt="" > 
                        <h2 class="text-center">LOGIN </h2>
                        <?php $fattr = array('class' => '');
                            echo form_open('', $fattr);
                        ?>
                        <div class="field" >
                        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                            
                            <?php
                            echo form_input(array(
                                'name' => 'email',
                                'id' => 'email',
                                'placeholder' => 'Username',
                                'class' => 'form-control border',
                                'value' => set_value('text'),
                                'required' => 'true'
                            ));
                            ?>
                        </div>
                        <?php echo form_error('email') ?>
                        <!-- <input type="text" class="form-control" placeholder="Email" required autofocus> -->
                        <div class="field mt-3">
                            
                            <?php
                            echo form_password(array(
                                'name' => 'password',
                                'id' => 'password',
                                'placeholder' => 'Password',
                                'class' => 'form-control border',
                                'value' => set_value('password'),
                                'required' => 'true'
                            ));
                            ?>
                        </div>
                        <?php echo form_error('password') ?>
                        <div class="pass  mt-3" >
                            <span id="captcha"><?php echo $image ?></span>&nbsp; <a class="refreshCaptcha" href="javascript:void(0);"> <i class="fa fa-refresh" aria-hidden="true"></i></a>
                        </div>
                        <div class="field space  mt-3">
                           
                            <input type="text" required name="captcha" placeholder="Please enter captcha code" autocomplete="off" class="form-control border">
                        </div>
                        <?php echo form_error('captcha') ?>
                        <div class="w-100  mt-3 mb-4">
                            <!-- <span><img src="<?php echo base_url();?>public/img/icon/login-icon.png" alt="user icon" height="20px" width="20px"></span> -->
                            <?php echo form_submit(array('value' => 'Login', 'class' => 'btn btn-lg btn-success green-btn w-100', )); ?>
                        </div>
                            <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
            </div>
        
        
    </div>
    <style>
        .green-btn {
    background: #636426;
}
    </style>
<script>
$(document).ready(function(){
    $('.refreshCaptcha').on('click', () => {
    $.get('<?php echo base_url() . 'Login/captcha?ci=1'; ?>', function(data) {
        $('#captcha').html(data);
    });
    });
    // setTimeout(() => {
    //   $.get('<?php echo base_url() . 'Login/captcha?ci=1'; ?>', function(data) {
    //     $('#captcha').html(data);
    //   });
    // }, 15000);

    $.notify.defaults({position: 'top center',style: 'bootstrap'});
        <?php if ($this->session->flashdata('flash_message')) { ?> 
        $.notify("<?php echo $this->session->flashdata('flash_message'); ?>", "error");
        <?php } ?>
});
</script>        

    <footer>
        <!-- <h6>  Powered By: NexSoftTec</h6> -->
    </footer> 

</body>

</html>
