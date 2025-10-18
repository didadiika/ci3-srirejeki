<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>LAPORAN PEMBELIAN</title>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/print.css" type="text/css" />

</head>
<style>
@media print {
input.noPrint { display: none; }
}
</style>
<body class="body">
<div id="wrapper">

<table width="100%">
<tr>
  <td width="10%" rowspan="3"></td>
  <td width="90%"><div class="kop1"><h2>MACTEL SRI REJEKI</h2></div></td>
</tr>
<tr>
  <td></td>
</tr>
</table>

  <h2 class="head" style="font-size:24px;">FORMULIR ABSENSI</h2>
  
  <table class="tabel" id="myTable">
  <thead>
  <tr>
  <td width="50%" style="font-size:20px;">TANGGAL MULAI :</td>
  <td width="50%" style="font-size:20px;"></td>
  </tr>
  <tr>
  <td width="50%" style="font-size:20px;">TANGGAL SELESAI :</td>
  <td width="50%" style="font-size:20px;"></td>
  </tr>
  </thead>
  </table>

  <?php
  
  if($karyawan->num_rows() > 0){
  ?>
  <table class="tabel" id="myTable" >
  <thead>
  <tr>
  <td style="font-size:20px;">No</td>
  <td style="font-size:20px;">Nama Karyawan</td>
  <td style="font-size:20px;">Senin</td>
  <td style="font-size:20px;">Selasa</td>
  <td style="font-size:20px;">Rabu</td>
  <td style="font-size:20px;">Kamis</td>
  <td style="font-size:20px;">Jum'at</td>
  <td style="font-size:20px;">Sabtu</td>
  <td style="font-size:20px;"><strong>Jumlah</strong></td>
  </tr>
  </thead>
  <tbody>
    <?php
    $no = 0;  
    foreach ($karyawan->result() as $p) {
    $no++;  
    ?>
  <tr>
  <td style="font-size:20px;"><?php echo $no;?></td>
  <td style="font-size:20px;"><?php echo $p->nama_karyawan;?></td>
  <td style="font-size:20px;"></td>
  <td style="font-size:20px;"></td>
  <td style="font-size:20px;"></td>
  <td style="font-size:20px;"></td>
  <td style="font-size:20px;"></td>
  <td style="font-size:20px;"></td>
  <td style="font-size:20px;"></td>
  </tr>
  <?php } ?>
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
<input type="button" onclick="window.print()" class="noPrint" value="Cetak Halaman">
<input type="button" onclick="window.close()" class="noPrint" value="Tutup">
</div>

</div>
</body>
</html>