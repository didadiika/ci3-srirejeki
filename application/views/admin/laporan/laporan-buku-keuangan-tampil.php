<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>LAPORAN BUKU KEUANGAN</title>
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

  <h2 class="head" style="font-size:24px;">LAPORAN BUKU KEUANGAN</h2>
  
  <table class="tabel" id="myTable">
  <thead>
  <tr>
  <td width="50%" style="font-size:20px;">Periode:</td>
  <td width="50%" style="font-size:20px;"><?php echo $lap["dari"]." sampai ".$lap["sampai"];?></td>
  </tr>
  </thead>
  </table>

 
  <table class="tabel" id="myTable" >
  <thead>
  <tr>
  <td style="font-size:20px;">No</td>
  <td style="font-size:20px;">Tanggal</td>
  <td style="font-size:20px;">Keterangan</td>
  <td style="font-size:20px;">Debit</td>
  <td style="font-size:20px;">Kredit</td>
  <td style="font-size:20px;">Saldo</td>
  </tr>
  </thead>
  <tbody>
  <tr>
  <td colspan="5" style="font-size:20px;"><strong>Saldo Awal<strong></td>
  <td align="right" style="font-size:20px;"><strong><?php echo uang($saldo);?></strong></td>    
  </tr>
    <?php
    if($gaji->num_rows() > 0){
    $no = 0;  
    foreach ($gaji->result() as $p) {
    $no++;  
    ?>
  <tr>
  <td style="font-size:20px;"><?php echo $no;?></td>
  <td style="font-size:20px;"><?php echo tgl_indo($p->tanggal);?></td>
  <td style="font-size:20px;"><?php echo $p->keterangan;?></td>
  <td align="right" style="font-size:20px;"><?php echo uang($p->debit);?></td>
  <td align="right" style="font-size:20px;"><?php echo uang($p->kredit);?></td>
  <td align="right" style="font-size:20px;"><?php echo uang($saldo = $saldo + $p->debit - $p->kredit);?></td>
  </tr>
  <?php 
  $deb[] = $p->debit;
  $kre[] = $p->kredit;
  } ?>
  <tr>
    <td colspan="3" style="font-size:20px;"><strong>Total</strong></td>
    <td align="right" style="font-size:20px;"><strong><?php echo uang(array_sum($deb));?></strong></td>
    <td align="right" style="font-size:20px;"><strong><?php echo uang(array_sum($kre));?></strong></td>
    <td align="right" style="font-size:20px;"><strong><?php echo uang($saldo);?></strong></td>
  </tr>
  <?php } else{ ?>
    <tr>
  <td colspan="6" align="center" style="font-size:20px;"><strong>Tidak Ada Transaksi</strong></td>
  </tr>
  <?php
  } ?>
  </tbody>
</table>

<br>
<div align="center">
<input type="button" onclick="window.print()" class="noPrint" value="Cetak Halaman">
<input type="button" onclick="window.close()" class="noPrint" value="Tutup">
</div>

</div>
</body>
</html>