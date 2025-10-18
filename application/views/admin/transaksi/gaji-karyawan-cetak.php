<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>SLIP GAJI</title>
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
  <td align="center" colspan="2"><span style="font-size:36px;">MACTEL SRI REJEKI</span></td>
</tr>
</table>

<table class="tabel">
  <tbody>
  <tr>
  <td width="25%" style="font-size:18px;">Alamat:</td>
  <td width="25%"  style="font-size:18px;"><strong>Mejobo</strong></td>
  </tr>
  <tr>
  <td width="25%" style="font-size:20px;">No Hp:</td>
  <td width="25%"  style="font-size:20px;"><strong>085 325 019 101</strong></td>
  </tr>
</tbody>
  </table>

  <h2 class="head" style="font-size:26px;">SLIP GAJI GABUNGAN</h2>
  
  <?php
  if($gaji->num_rows() > 0){

  foreach($gaji->result() as $p){
  ?>

  <table class="tabel" id="myTable">
  <thead>
  <tr>
  <td width="25%" style="font-size:20px;">Tanggal Gaji:</td>
  <td width="25%"  style="font-size:20px;"><strong><?php echo tgl_indo($p->tanggal);?></strong></td>
  </tr>
  </thead>
  </table>

<?php
  
if($karyawan->num_rows() > 0){
?>
  <table class="tabel" id="myTable" >
  <thead>
  <tr>
  <td style="font-size:18px;">No</td>
  <td style="font-size:18px;">Karyawan</td>
  <td style="font-size:18px;">Gaji Satuan</td>
  <td style="font-size:18px;">Jumlah</td>
  <td style="font-size:18px;">Potongan</td>
  <td style="font-size:18px;">Sub Total</td>
  </tr>
  </thead>
  <tbody>
  
  <?php 
  $b = array();
  $no = 0;
  foreach($karyawan->result() as $m){
$no++;
  ?>
<tr>
  <td style="font-size:18px;"><?php echo $no;?></td>
  <td style="font-size:18px;"><?php echo $m->nama_karyawan;?></td>
  <td style="font-size:18px;"><?php echo uang($m->gaji);?></td>
  <td style="font-size:18px;"><?php echo uang($m->qty);?></td>
  <td style="font-size:18px;"><?php echo uang($m->potongan);?></td>
  <td style="font-size:18px;"><?php echo uang($b[] = (int)$m->sub_total);?></td>
  </tr>

  <?php } ?>
  <tr>
  <td style="font-size:20px;" colspan="5"><b>TOTAL</b></td>
  <td style="font-size:20px;"><b><?php echo uang(array_sum($b));?></b></td>
  </tr>
  <tbody>
  </table>

  <table width="100%">
  <tr>
    <td style="text-align: center; width: 25%;">Owner</td>
    <td style="text-align: center;  width: 25%;">Admin</td>
  </tr>
</table>
<br>
<br>
<br>
<table width="100%">
  <tr>
  <td style="text-align: center; width: 25%;">_____________</td>
    <td style="text-align: center;  width: 25%;">_____________</td>
  </tr>
  <tr>
  <td style="text-align: center; width: 25%;"></td>
    <td style="text-align: center;  width: 25%;"></td>
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