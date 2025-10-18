<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="shortcut icon" href="<?php echo base_url('assets/dist/img/mactel-icon.png');?>">
  <title>SR | Login</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/AdminLTE.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/iCheck/square/blue.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
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
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="index.php"><b>MACTEL Sri</b> Rejeki</a>
  </div>
  <!-- /.login-logo -->
  <?php 
  $sekarang = time();
  $masa_berlaku = strtotime($masa_berlaku);
  $sisa = $masa_berlaku - $sekarang;
  $hari = floor($sisa / (24*60*60));
  $error_login = $this->session->flashdata('message');

  if($hari <=5 && $hari >= 0){
  ?>
  <div class="alert alert-danger alert-dismissible">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      <h4><i class="icon fa fa-ban"></i> Peringatan!</h4>
                Masa berlaku aplikasi anda tinggal <strong> <?php echo $hari;?> Hari</strong> lagi. Perpanjang langganan sebelum hangus. 
  </div>
  <?php } else if($hari < 0){ ?> 
  <div class="alert alert-danger alert-dismissible">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      <h4><i class="icon fa fa-ban"></i> Peringatan!</h4>
                Masa berlaku hosting program anda sudah habis. Hubungi penyedia hosting anda. 
  </div>
  <?php } 
  if(!empty($error_login))
  {
    ?>
     <div class="alert alert-danger alert-dismissible">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      <h4><i class="icon fa fa-ban"></i> Peringatan!</h4>
                <?php echo $error_login;?>
  </div>
    <?php
  }
  ?>
  <div class="login-box-body">
    <p class="login-box-msg">Masukkan data akun anda</p>

    <form action="<?php echo base_url("login/aksi_login");?>" method="post">
      <div class="form-group has-feedback">
        <input type="text" name="username" id="username" class="form-control" placeholder="Username" required <?php if($hari < 0 ){echo "disabled";}?> >
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" name="password" id="password" class="form-control" placeholder="Password" required <?php if($hari < 0 ){echo "disabled";}?>>
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="pull-right">
          <div class="checkbox icheck" id="lihat-pass">
            <label><i id="eye" class="fa fa-eye-slash"></i> Lihat Password
            </label>
          </div>
        </div>
      <div class="row">
        <!--<div class="col-xs-8">
          <div class="checkbox icheck">
            <label>
              <input type="checkbox"> Ingat Saya
            </label>
          </div>
        </div>
        <!-- /.col -->
        <div class="col-xs-4">
          <button type="submit" class="btn btn-primary btn-block btn-flat" <?php if($hari < 0 ){echo "disabled";}?>>Login</button>
        </div>
        <!-- /.col -->
      </div>
    </form>

    
    <!-- /.social-auth-links -->

    

  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 3 -->
<script src="<?php echo base_url(); ?>assets/bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="<?php echo base_url(); ?>assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="<?php echo base_url(); ?>assets/plugins/iCheck/icheck.min.js"></script>
<script>
  $(function () {
    $('#lihat-pass').click(function(){
      if($('#password').attr('type')  == 'password')
      {
        $('#password').prop('type', 'text');
        $("#eye").attr("class","fa fa-eye");
      }
      else
      {
        $('#password').prop('type', 'password');
        $("#eye").attr("class","fa fa-eye-slash");
      }
      
    });

    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' /* optional */
    });
  });
</script>
</body>
</html>
