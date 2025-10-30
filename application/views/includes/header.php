<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <title>Basil LIMS</title>
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="<?php echo base_url('assets/plugins/fontawesome-free/css/all.min.css') ?> ">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="<?php echo base_url('assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') ?>">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url('assets/dist/css/adminlte.min.css') ?>">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <!-- Select2 Dropdown CSS -->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
  <!-- Custom CSS -->
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/custom/custom.css') ?>">
  <!-- Date Picker -->
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.css">
  <!-- jQuery -->
  <script src="<?php echo base_url('assets/plugins/jquery/jquery.min.js'); ?>"></script>
  <!-- Favicon -->
  <link rel="icon" href="<?php echo base_url('public/img/logo/logp.png') ?>" type="image/png" sizes="16x16">
  <meta charset="utf-8" value="<?php echo $this->security->get_csrf_hash(); ?>" name="<?php echo $this->security->get_csrf_token_name(); ?>">
</head>
<body class="hold-transition layout-top-nav" data-url="<?php echo base_url(); ?>">
<!-- <body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed" data-url="<?php echo base_url(); ?>"> -->
<div class="wrapper">
<style>
    .pageloader {
		position: fixed;
		left: 0px;
		top: 0px;
		width: 100%;
		height: 100%;
		z-index: 999999;
		background: url('<?php echo base_url('public/img/loader.gif'); ?>') 50% 50% no-repeat rgb(249, 249, 249);
		opacity: .8;
	}
  </style>
  <?php if ($this->session->has_userdata('saved_timezone')) { ?>
      <script>
        $(document).ready(function() {
          var _tokken = $('meta[name="_tokken"]').attr('value');
          var timezone_offset_minutes = new Date().getTimezoneOffset();
          timezone_offset_minutes = (timezone_offset_minutes == 0 ? 0 : -timezone_offset_minutes);
          $.ajax({
            type: 'post',
            url: '<?php echo base_url('Login/save_time_zone'); ?>',
            data: {
              _tokken: _tokken,
              time:timezone_offset_minutes
            },
            success: function(data) {
              console.log('done');
            }
          });
        });
      </script>
    <?php } ?>
