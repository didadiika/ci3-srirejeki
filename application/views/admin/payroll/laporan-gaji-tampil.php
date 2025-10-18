<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>LAPORAN GAJI</title>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/print.css" type="text/css" />

</head>
<style>
@media print {
input.noPrint { display: none; }
}
</style>
<body class="body">
<div id="wrapper">

  <h2 class="head">LAPORAN GAJI</h2>
  
  <table class="tabel" id="myTable">
  <thead>
  <tr>
  <td width="50%">Periode:</td>
  <td width="50%"><?php echo $lap["bulan"]." ".$lap["tahun"];?></td>
  </tr>
  </thead>
  </table>

  <?php
  
  if($gaji->num_rows() > 0){
  ?>
  <table class="tabel" id="myTable">
  <thead>
  <tr>
  <td>No</td>
  <td>Tanggal</td>
  <td>Minggu Ke</td>
  <td>No Slip</td>
  <td>Pegawai</td>
  <td>Jabatan</td>
  <td>Total</td>
  </tr>
  </thead>
  <tbody>
    <?php
    $no = 0;  
    foreach ($gaji->result() as $p) {
    $no++;  
    ?>
  <tr>
  <td><?php echo $no;?></td>
  <td><?php echo tgl_db($p->tanggal);?></td>
  <td><?php echo $p->minggu_ke;?></td>
  <td><?php echo $p->no_slip;?></td>
  <td><?php echo $p->nama_pegawai;?></td>
  <td><?php echo $p->jabatan;?></td>
  <td align="right"><?php echo uang($p->total);?></td>
  </tr>
  <?php 
  $tot[] = $p->total;
  } ?>
  <tr>
    <td colspan="6"><strong>Total</strong></td>
    <td align="right"><strong><?php echo uang(array_sum($tot));?></strong></td>
  </tr>
  </tbody>
</table>

<?php
}
else
{
  echo"
<div align='center'><strong><h3>Transaksi Tidak ditemukan !</h3></strong></div>
  ";
}
?>

<br>
<div align="center">
<button type="button" onclick="window.print()">Cetak Halaman</button>
</div>

</div>
</body>
</html>