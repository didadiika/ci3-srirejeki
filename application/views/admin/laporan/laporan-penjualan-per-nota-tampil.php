<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>LAPORAN PENJUALAN</title>
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

  <h2 class="head" style="font-size:24px;">LAPORAN PENJUALAN</h2>
  
  <table class="tabel" id="myTable">
  <thead>
  <tr>
  <td width="50%" style="font-size:20px;">Jenis Laporan:</td>
  <td width="50%" style="font-size:20px;"><?php echo $jenis;?></td>
  </tr>
  <tr>
  <td width="50%" style="font-size:20px;">Periode:</td>
  <td width="50%" style="font-size:20px;"><?php echo $lap["dari"]." sampai ".$lap["sampai"];?></td>
  </tr>
  <tr>
  <td width="50%" style="font-size:20px;">Pelanggan:</td>
  <td width="50%" style="font-size:20px;"><?php echo $pelanggan;?></td>
  </tr>
  </thead>
  </table>

  <?php
  
  if($gaji->num_rows() > 0){
  ?>
  <table class="tabel" id="myTable" >
  <thead>
  <tr>
  <td style="font-size:20px;">No</td>
  <td style="font-size:20px;">Tanggal</td>
  <td style="font-size:20px;">Pelanggan</td>
  <td style="font-size:20px;">Tonase</td>
  <td style="font-size:20px;">Total Invoice</td>
  <td style="font-size:20px;">Sudah Bayar</td>
  <td style="font-size:20px;">Piutang</td>
  </tr>
  </thead>
  <tbody>
    <?php
    $no = 0;  
    foreach ($gaji->result() as $p) {
    $no++;  
    $s = $this->db->query("select sum(bayar) as bayar from invoice_bayar where id_invoice='$p->id_invoice' and deleted_at is null");
    $sudah_bayar = 0;
    if($s->num_rows() > 0){
      foreach($s->result() as $t){
        $sudah_bayar = (int)$t->bayar;
      }
    }
    ?>
  <tr>
  <td style="font-size:20px;"><?php echo $no;?></td>
  <td style="font-size:20px;"><?php echo tgl_db($p->tanggal);?></td>
  <td style="font-size:20px;"><?php echo $p->nama_pelanggan;?></td>
  <td align="right" style="font-size:20px;"><?php echo uang($p->total_tonase);?></td>
  <td align="right" style="font-size:20px;"><?php echo uang($p->total);?></td>
  <td align="right" style="font-size:20px;"><?php echo uang($sudah_bayar);?></td>
  <td align="right" style="font-size:20px;"><?php echo uang($p->total - $sudah_bayar);?></td>
  </tr>
  <?php 
  $ber[] = $p->total;
  $ton [] = $p->total_tonase;
  $sud [] = $sudah_bayar;
  $piu [] = $p->total - $sudah_bayar;
  } ?>
  <tr>
    <td colspan="3" style="font-size:20px;"><strong>Total</strong></td>
    <td align="right" style="font-size:20px;"><strong><?php echo uang(array_sum($ton));?></strong></td>
    <td align="right" style="font-size:20px;"><strong><?php echo uang(array_sum($ber));?></strong></td>
    <td align="right" style="font-size:20px;"><strong><?php echo uang(array_sum($sud));?></strong></td>
    <td align="right" style="font-size:20px;"><strong><?php echo uang(array_sum($piu));?></strong></td>
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