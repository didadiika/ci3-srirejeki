<?php
/**
 * Kerangka atas laporan cetak (dipakai semua laporan *-tampil).
 * Variabel : $judul  (mis. "LAPORAN PENJUALAN")
 *            $info   (array label => nilai, ditampilkan di bawah judul; nilai sudah aman/escaped bila perlu)
 */
if (!function_exists('rp_lap')) {
    /** Format angka gaya Indonesia; desimal hanya tampil jika ada sen */
    function rp_lap($n){
        $n   = round((float) $n, 2);
        $dec = (abs($n - floor($n)) > 0.004) ? 2 : 0;
        return number_format($n, $dec, ',', '.');
    }
}
if (!function_exists('persen_lap')) {
    function persen_lap($bagian, $total){
        return $total > 0 ? number_format($bagian / $total * 100, 1, ',', '.') . '%' : '0%';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo $judul; ?></title>
<style>
  * { box-sizing: border-box; }
  body { font-family: Arial, Helvetica, sans-serif; color: #222; margin: 0; background: #f2f2f2; }
  #wrapper { max-width: 1100px; margin: 20px auto; background: #fff; padding: 28px 32px; box-shadow: 0 1px 4px rgba(0,0,0,.15); }
  .kop { border-bottom: 3px double #333; padding-bottom: 8px; margin-bottom: 14px; }
  .kop h2 { margin: 0; font-size: 22px; letter-spacing: .5px; }
  h1.judul { text-align: center; font-size: 20px; margin: 6px 0 14px; letter-spacing: 1px; }
  table { width: 100%; border-collapse: collapse; }
  .info td { padding: 3px 0; font-size: 14px; vertical-align: top; }
  .info td.l { width: 140px; color: #555; }

  .ringkasan { display: flex; gap: 10px; margin: 16px 0 18px; }
  .ringkasan .kotak { flex: 1; border: 1px solid #ccc; border-left-width: 4px; border-radius: 4px; padding: 10px 12px; }
  .ringkasan .kotak .lbl { font-size: 12px; color: #666; text-transform: uppercase; letter-spacing: .4px; }
  .ringkasan .kotak .val { font-size: 20px; font-weight: bold; margin-top: 4px; }
  .ringkasan .kotak .sub { font-size: 12px; color: #777; margin-top: 2px; }
  .k-biru  { border-left-color: #3c8dbc !important; }
  .k-hijau { border-left-color: #00a65a !important; } .k-hijau .val { color: #1e8449; }
  .k-merah { border-left-color: #dd4b39 !important; } .k-merah .val { color: #c0392b; }
  .k-kuning{ border-left-color: #f39c12 !important; }
  .k-abu   { border-left-color: #777 !important; }

  h3.sub-judul { font-size: 15px; margin: 18px 0 6px; }
  table.data th, table.data td { border: 1px solid #999; padding: 5px 7px; font-size: 13px; }
  table.data thead th { background: #eee; text-align: center; }
  table.data td.r, table.data th.r { text-align: right; white-space: nowrap; }
  table.data td.c, table.data th.c { text-align: center; }
  table.data td.nw { white-space: nowrap; }
  table.data tfoot th { background: #f5f5f5; }
  table.data tr.grup td { background: #f7f9fc; font-weight: bold; }
  table.data tr.subtotal td { background: #fafafa; font-style: italic; }
  table.data tr.saldo td { background: #f7f9fc; font-weight: bold; }
  .masuk { color: #1e8449; } .keluar { color: #c0392b; } .redup { color: #aaa; }
  .badge { display: inline-block; padding: 1px 7px; border-radius: 9px; font-size: 11px; font-weight: bold; color: #fff; white-space: nowrap; }
  .b-hijau { background: #00a65a; } .b-kuning { background: #f39c12; } .b-merah { background: #dd4b39; } .b-abu { background: #888; }
  .kosong { text-align: center; padding: 30px 0; color: #777; }
  .dicetak { font-size: 11px; color: #888; margin-top: 18px; }
  .tombol { text-align: center; margin-top: 24px; }
  .tombol input { padding: 7px 18px; margin: 0 4px; cursor: pointer; }

  @media (max-width: 700px) {
    #wrapper { margin: 0; padding: 16px; }
    .ringkasan { flex-wrap: wrap; } .ringkasan .kotak { flex: 1 1 45%; }
    .scroll-x { overflow-x: auto; }
  }
  @media print {
    body { background: #fff; }
    #wrapper { box-shadow: none; margin: 0; max-width: none; padding: 0; }
    .noPrint { display: none !important; }
    .badge { color: #000; border: 1px solid #666; background: none !important; }
    tr { page-break-inside: avoid; }
    thead { display: table-header-group; }
    @page { size: A4 landscape; margin: 12mm; }
  }
</style>
</head>
<body>
<div id="wrapper">

  <div class="kop"><h2>MACTEL SRI REJEKI</h2></div>
  <h1 class="judul"><?php echo $judul; ?></h1>

  <?php if (!empty($info)) { ?>
  <table class="info">
    <?php foreach ($info as $label => $nilai) { ?>
    <tr><td class="l"><?php echo $label; ?></td><td>: <?php echo $nilai; ?></td></tr>
    <?php } ?>
  </table>
  <?php } ?>
