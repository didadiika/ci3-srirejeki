<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>HISTORY PEMBAYARAN</title>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/print-modal.css" type="text/css" />
</head>
<style>
@media print {
input.noPrint { display: none; }
}
</style>
<body class="body">
<div id="wrapper">

  
  <?php
  if($pembayaran->num_rows() > 0){
  if($pembelian->num_rows() > 0){

  foreach($pembelian->result() as $p){
  ?>
  
  <h2 class="head" style="font-size:26px;">HISTORY PEMBAYARAN</h2>
  <table class="tabel" id="myTable">
  <thead>
  <tr>
  <td>Tanggal:</td>
  <td><strong><?php echo tgl_indo($p->tanggal);?></strong></td>
  <td>Pengirim:</td>
  <td><strong><?php echo $p->nama_pengirim;?></strong></td>
  </tr>
  </thead>
  </table>

<?php
}
  

?>
  <table class="tabel" id="myTable" >
  <thead>
  <tr>
  <td>Tanggal</td>
  <td>Nominal</td>
  </tr>
  </thead>
  <tbody>
  
  <?php 
  $b = array();
  $no = 0;
  foreach($pembayaran->result() as $m){
$no++;
  ?>
<tr>
  <td><?php echo tgl_indo($m->tanggal);?></td>
  <td><?php echo uang($b[] = (int) $m->bayar);?></td>
  </tr>

  <?php } ?>
  <tr>
  <td><b>TOTAL PEMBAYARAN</b></td>
  <td><b><?php echo uang(array_sum($b));?></b></td>
  </tr>
  <tbody>
  </table>


<?php
  }
}
else
{
  echo"
<div align='center'><strong><h3>Belum Ada Pembayaran !</h3></strong></div>
  ";
}
?>



</div>
</body>
</html>