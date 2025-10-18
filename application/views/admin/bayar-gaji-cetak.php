<?php
if($slip->num_rows() > 0){
foreach($slip->result() as $r){
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Cetak Slip Gaji</title>
<link rel="stylesheet" href="<?php echo base_url();?>assets/dist/css/slip.css" type="text/css"  />
</head>

<body class="body">
<div id="wrapper">
<?php

?>
<table width="100%">
<tr>
  <td width="10%" rowspan="3"><img src="<?php echo base_url("assets/foto/logo.png");?>" width="90" height="80"></td>
  <td width="90%"><div class="kop1"><h2>ARFA KARYA MANDIRI</h2></div></td>
</tr>
<tr>
  <td><div class="kop2alamat">Dusun 1, Pringtulis, Kec. Nalumsari, Kabupaten Jepara, Jawa Tengah 59466</div></td>
</tr>
</table>





<h2 class='head'></h2>
<div class="po-judul">TANDA TERIMA SLIP GAJI</div>
<div class="po-nomor">No. <?php echo $r->no_slip;?></div>


<div class="kepada">
Kepada Yang Terhormat:</br>
<table class="tabel-gaji">
<tr>
    <td>Nama :</td>
    <td><strong><?php echo $r->nama_pegawai;?></strong></td>
    <td>Periode Gaji :</td>
    <td><?php echo month_to_bulan($r->bulan)." ".$r->tahun;?></td>
</tr>
<tr>
    <td>Jabatan :</td>
    <td><strong><?php echo $r->jabatan;?></strong></td>
    <td>Minggu Ke :</td>
    <td><?php echo $r->minggu_ke;?></td>
</tr>
<tr>
    <td>Alamat :</td>
    <td><strong><?php echo $r->alamat;?></strong></td>
    <td>Tanggal Slip :</td>
    <td><?php echo tgl_pecah($r->tanggal);?></td>
</tr>
</table>
</div>


<br>

<div class="paragraf">
Daftar rincian slip gaji anda :
</div>


<table class='tabel'>
  <thead>
  <tr>
  <td width="5%">No</td>
  <td>Jenis Gaji</td>
  <td>Qty x Nominal</td>
  <td>Sub Total</td>
  </tr>
  </thead>
  <tbody>
    <?php
    $t = array();
    $no = 0;
     foreach($list->result() as $d){
      $no++;
    ?>
    <tr>
    <td><?php echo $no;?></td>
    <td><?php echo $d->nama_gaji;?></td>
    <td><?php echo $d->qty ." x ".uang($d->gaji_satuan);?></td>
    <td align="right"><?php echo uangRp($d->sub_total);?></td>
  </tr>
<?php
$t[] = $d->sub_total;
     }
?>
<tr>
  <td colspan="3"><strong>Total</strong></td>
  <td align="right"><strong><?php echo uangRp(array_sum($t));?></strong></td>
</tr>
  </tbody>
</table>

<br>

<div class="paragraf">

<br>


<table class='tabel-keterangan'>
<tr>
  <td>Keterangan : <?php echo $r->keterangan;?></td>
</tr>
  </tbody>
</table>





<table width="100%">
<tr>
    <td style="text-align: center; width: 50%;"></td>
    <td style="text-align: center;  width: 50%;">Jepara, <?php echo tgl_indo(date("Y-m-d"));?></td>
  </tr>
  <tr>
    <td style="text-align: center; width: 50%;"></td>
    <td style="text-align: center;  width: 50%;"></td>
  </tr>
  <tr>
    <td style="text-align: center; width: 50%;">Penerima</td>
    <td style="text-align: center;  width: 50%;">Arfa Karya Mandiri</td>
  </tr>
  <tr>
    <td style="text-align: center; width: 50%;"></td>
    <td style="text-align: center;  width: 50%;"></td>
  </tr>
</table>
<br>
<br>
<br>
<table width="100%">
  <tr>
    <td style="text-align: center; width: 50%;">(<?php echo $r->nama_pegawai;?>)</td>
    <td style="text-align: center;  width: 50%;">(Andi Fahrizal)</td>
  </tr>
</table>

</div>
<br>
  <div style="text-align:center;padding:20px;">
  <input class="noPrint" type="button" value="Cetak" onClick="window.print()">
  <input class="noPrint" type="button" value="Selesai" onClick="window.close()">
  

  </div>
</div>

</body>
</html>
<?php
}
}
?>