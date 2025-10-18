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

  <h2 class="head" style="font-size:24px;">LAPORAN PEMBELIAN</h2>
  
  <table class="tabel" id="myTable">
  <thead>
  <tr>
  <td width="50%" style="font-size:20px;">Periode:</td>
  <td width="50%" style="font-size:20px;"><?php echo $lap["dari"]." sampai ".$lap["sampai"];?></td>
  </tr>
  <tr>
  <td width="50%" style="font-size:20px;">Pengirim:</td>
  <td width="50%" style="font-size:20px;"><?php echo $pengirim;?></td>
  </tr>
  </thead>
  </table>

  <?php
  $paid = 0;
  if($gaji->num_rows() > 0){
  ?>
  <table class="tabel" id="myTable" >
  <thead>
  <tr>
  <td style="font-size:20px;">No</td>
  <td style="font-size:20px;">Tanggal</td>
  <td style="font-size:20px;">No Truk</td>
  <td style="font-size:20px;">Pengirim</td>
  <td style="font-size:20px;">Harga Satuan</td>
  <td style="font-size:20px;">Tonase</td>
  <td style="font-size:20px;">Total Beli</td>
  </tr>
  </thead>
  <tbody>
    <?php
    $no = 0;  
    foreach ($gaji->result() as $p) {
    $no++;  
    ?>
  <tr>
  <td style="font-size:20px;"><?php echo $no;?></td>
  <td style="font-size:20px;"><?php echo tgl_db($p->tanggal);?></td>
  <td style="font-size:20px;"><?php echo $p->no_polisi;?></td>
  <td style="font-size:20px;"><?php echo $p->nama_pengirim;?></td>
  <td align="right" style="font-size:20px;"><?php echo uang($p->harga_satuan);?></td>
  <td align="right" style="font-size:20px;"><?php echo uang($p->total_tonase);?></td>
  <td align="right" style="font-size:20px;"><?php echo uang($p->harga_satuan * $p->total_tonase);?></td>
  </tr>
  <?php 
  $ber[] = $p->total_tonase;
  $sat[] = $p->harga_satuan * $p->total_tonase;
  } ?>
  <tr>
    <td colspan="5" style="font-size:20px;"><strong>Total</strong></td>
    <td align="right" style="font-size:20px;"><strong><?php echo uang(array_sum($ber));?></strong></td>
    <td align="right" style="font-size:20px;"><strong><?php echo uang(array_sum($sat));?></strong></td>
  </tr>
  <tr>
    <td colspan="5" style="font-size:20px;"><strong>Sudah Bayar (DP)</strong></td>
    <td colspan="2" align="right" style="font-size:20px;"><strong>
    <?php
    if($sudah_bayar->num_rows() > 0){
      foreach ($sudah_bayar->result() as $d) {
        $paid = $d->paid;
      }
    }
    echo uang($paid);
    ?>
    </strong>
    </td>
  </tr>
  <tr>
    <td colspan="5" style="font-size:20px;"><strong>Utang</strong></td>
    <td colspan="2" align="right" style="font-size:20px;"><strong><?php echo uang( array_sum($sat) - $paid);?></strong></td>
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
<input type="button" onclick="window.print()" class="noPrint" value="Cetak Halaman">
<input type="button" onclick="window.close()" class="noPrint" value="Tutup">
</div>

</div>
</body>
</html>