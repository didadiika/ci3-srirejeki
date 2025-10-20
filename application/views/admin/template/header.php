<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="shortcut icon" href="<?php echo base_url('assets/dist/img/mactel-icon.png');?>">
  <title>SR | Admin</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!--CSS Global-->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/bootstrap/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/Ionicons/css/ionicons.min.css">
  
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/skins/_all-skins.min.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

  <!--CSS Halaman-->
  	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/select2/dist/css/select2.min.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/bootstrap-daterangepicker/daterangepicker.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/select2/dist/css/select2.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/bootstrap-toggle/css/bootstrap-toggle.min.css">
  	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/AdminLTE.min.css">

<script src="<?php echo base_url(); ?>assets/bower_components/jquery/dist/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/dist/js/uang.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/dist/js/JsBarcode.all.min.js"></script>

<script type="text/javascript">
function backup_db(){
    $.ajax({
      url:"<?php echo base_url('backup');?>",
      type:"POST",
      data:{},
      success:function(){
        console.log("Berhasil Auto Backup DB");
      },
      error:function(){
        console.log("Gagal Auto Backup DB");
      }
    });
}

setTimeout(function(){ backup_db(); }, 100); /*0,1 Detik*/
setInterval(function(){ backup_db(); }, 300000); /*5 Menit*/
</script>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <a href="<?php echo base_url();?>" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>S</b>R</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>Sri Rejeki</b></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>

      
    </nav>
  </header>
