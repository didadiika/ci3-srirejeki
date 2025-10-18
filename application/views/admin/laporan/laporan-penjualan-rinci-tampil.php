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
  <td style="font-size:20px;">Barang</td>
  <td style="font-size:20px;">Harga</td>
  <td style="font-size:20px;">Tonase(Kg)</td>
  <td style="font-size:20px;">Sub Total</td>
  </tr>
  </thead>
  <tbody>
    <?php
    $no = 0;  
    $u = array();
    $u[0] = '';
    $sub = array();
    foreach ($gaji->result() as $p) {
    $no++;  
    $u[$no] = $p->id_invoice;
?>
<tr>
    <?php
    if($p->id_invoice != $u[$no - 1]){
        $b = $this->db->query("select * from invoice_d where id_invoice='$p->id_invoice' ");
        $b = $b->num_rows();
    ?>
  <td style="font-size:20px;" rowspan="<?php echo $b;?>"><?php echo $no;?></td>
  <td style="font-size:20px;" rowspan="<?php echo $b;?>"><?php echo tgl_db($p->tanggal);?></td>
  <td style="font-size:20px;" rowspan="<?php echo $b;?>"><?php echo $p->nama_pelanggan;?></td>
    <?php
    } else {

    }
    ?>
  <td style="font-size:20px;"><?php echo $p->nama_barang;?></td>
  <td align="right" style="font-size:20px;"><?php echo uang($p->harga);?></td>
  <td align="right" style="font-size:20px;"><?php echo uang($p->qty);?></td>
  <td align="right" style="font-size:20px;"><?php echo uang($sub[] = $p->sub_total);?></td>
  </tr>
  <?php
  } 
  ?>
  <tr>
    <td colspan="6" style="font-size:20px;"><strong>Total</strong></td>
    <td align="right" style="font-size:20px;"><strong><?php echo uang(array_sum($sub));?></strong></td>
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