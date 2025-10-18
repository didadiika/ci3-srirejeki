<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>LAPORAN PEMBELIAN</title>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/print.css" type="text/css" />
<style>
.grid-container {
  display: grid;
  grid-template-columns: 3fr 3fr 3fr 3fr 3fr 3fr 3fr 3fr 3fr 3fr;
  grid-template-rows: 30px 30px 30px 30px 30px 30px 30px 30px 30px 30px;
}
.grid-container > div {
  border: 1px solid black;
  text-align: center;
  font-size: 20px;
}
/* .grid {
    display: grid;
    grid-template-columns: repeat(5, lfr);
    border-top: 1px solid black;
    border-right: 1px solid black;
}

.grid > span {
    padding: 8px 4px;
    border-left: 1px solid black;
    border-bottom: 1px solid black;
} */
</style>
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
  <td width="90%" align="center"><span style="font-size:36px;">MACTEL SRI REJEKI</span></td>
</tr>
<tr>
  <td></td>
</tr>
</table>

  <h2 class="head" style="font-size:20px;">LAPORAN PEMBELIAN TIMBANGAN</h2>
  
  <?php
  if($pembelian->num_rows() > 0){

  foreach($pembelian->result() as $p){
  ?>
  <table class="tabel" id="myTable">
  <thead>
  <tr>
  <td width="25%" style="font-size:18px;">Tanggal:</td>
  <td width="25%"  style="font-size:18px;"><strong><?php echo tgl_indo($p->tanggal);?></strong></td>
  <td width="25%"  style="font-size:18px;">Pengirim:</td>
  <td width="25%" style="font-size:18px;"><strong><?php echo $p->nama_pengirim;?></strong></td>
  </tr>
  <tr>
  <td width="25%" style="font-size:18px;">No Truk:</td>
  <td width="25%" style="font-size:18px;"><strong><?php echo $p->no_polisi;?></strong></td>
  <td width="25%" style="font-size:18px;">Harga Satuan KBK:</td>
  <td width="25%" style="font-size:18px;"><strong><?php echo uang($p->harga_kbk);?></strong></td>
  </tr>
  <tr>
  <td width="50%" colspan="2" style="font-size:18px;">Harga Satuan Beli:</td>
  <td width="50%" colspan="2" style="font-size:18px;"><strong><?php echo uang($p->harga_satuan);?></strong></td>
  </tr>
  </thead>
  </table>

<?php
 $tim = ($p->timbangan == 0) ? 50000 : $p->timbangan; 

if($timbangan->num_rows() > 0){
?>
  
  <div class="grid-container">
  <?php 
  $b = array();
  foreach($timbangan->result() as $m){
  ?>
  <div><?php echo (int) $b[] = $m->bobot;?></div>
  <?php } ?>
</div>
<table class="tabel">
<tr>
    <td style="font-size:20px;"><strong>Total Tonase</strong></td>
    <td align="center" style="font-size:20px;"><strong><?php echo (int)$p->total_tonase;?></strong></td>
</tr>
<tr>
    <td style="font-size:20px;"><strong>Total Beli (<?php echo uang($p->harga_satuan);?> x <?php echo (int) $p->total_tonase;?>)</strong></td>
    <td align="center" style="font-size:20px;"><strong><?php echo uang ($p->total_tonase * $p->harga_satuan);?></strong></td>
</tr>
<tr>
    <td style="font-size:20px;"><strong>Total KBK (<?php echo uang($p->harga_kbk);?> x <?php echo (int)$p->total_tonase;?>) + Timbangan (<?php echo uang($tim);?>)</strong></td>
    <td align="center" style="font-size:20px;"><strong><?php echo uang (($p->total_tonase * $p->harga_kbk) + $tim);?></strong></td>
</tr>
<tr>
    <td style="font-size:20px;"><strong>Jumlah (x2)</strong></td>
    <td align="center" style="font-size:20px;"><strong><?php echo $timbangan->num_rows() * 2;?> Sak</strong></td>
</tr>
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
<?php } } ?>
</div>
</body>
</html>