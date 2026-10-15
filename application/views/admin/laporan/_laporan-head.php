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
  body { font-family: Arial, Helvetica, sans-serif; color: #111; margin: 0; background: #f2f2f2; font-size: 16px; }
  #wrapper { max-width: 1100px; margin: 20px auto; background: #fff; padding: 28px 32px; box-shadow: 0 1px 4px rgba(0,0,0,.15); }
  .kop { border-bottom: 3px double #333; padding-bottom: 8px; margin-bottom: 14px; }
  .kop h2 { margin: 0; font-size: 28px; letter-spacing: .5px; }
  h1.judul { text-align: center; font-size: 26px; margin: 6px 0 14px; letter-spacing: 1px; }
  table { width: 100%; border-collapse: collapse; }
  .info td { padding: 4px 0; font-size: 18px; vertical-align: top; }
  .info td.l { width: 170px; color: #333; }

  .ringkasan { display: flex; gap: 10px; margin: 16px 0 18px; }
  .ringkasan .kotak { flex: 1; border: 1px solid #ccc; border-left-width: 4px; border-radius: 4px; padding: 10px 12px; }
  .ringkasan .kotak .lbl { font-size: 14px; color: #444; text-transform: uppercase; letter-spacing: .4px; }
  .ringkasan .kotak .val { font-size: 24px; font-weight: bold; margin-top: 4px; }
  .ringkasan .kotak .sub { font-size: 14px; color: #555; margin-top: 2px; }
  .k-biru  { border-left-color: #3c8dbc !important; }
  .k-hijau { border-left-color: #00a65a !important; } .k-hijau .val { color: #1e8449; }
  .k-merah { border-left-color: #dd4b39 !important; } .k-merah .val { color: #c0392b; }
  .k-kuning{ border-left-color: #f39c12 !important; }
  .k-abu   { border-left-color: #777 !important; }

  h3.sub-judul { font-size: 19px; margin: 18px 0 6px; }
  table.data th, table.data td { border: 1px solid #777; padding: 7px 8px; font-size: 17px; }
  table.data thead th { background: #eee; text-align: center; }
  table.data td.r, table.data th.r { text-align: right; white-space: nowrap; }
  table.data td.c, table.data th.c { text-align: center; }
  table.data td.nw { white-space: nowrap; }
  table.data tfoot th { background: #f5f5f5; }
  table.data tr.grup td { background: #f7f9fc; font-weight: bold; }
  table.data tr.subtotal td { background: #fafafa; font-style: italic; }
  table.data tr.saldo td { background: #f7f9fc; font-weight: bold; }
  .masuk { color: #1e8449; } .keluar { color: #c0392b; } .redup { color: #aaa; }
  .badge { display: inline-block; padding: 2px 8px; border-radius: 9px; font-size: 14px; font-weight: bold; color: #fff; white-space: nowrap; }
  .b-hijau { background: #00a65a; } .b-kuning { background: #f39c12; } .b-merah { background: #dd4b39; } .b-abu { background: #888; }
  .kosong { text-align: center; padding: 30px 0; color: #777; }
  .dicetak { font-size: 14px; color: #555; margin-top: 18px; }
  .tombol { text-align: center; margin-top: 24px; }
  .tombol input { padding: 10px 22px; margin: 0 4px; cursor: pointer; font-size: 16px; }

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
    @page { size: A4 portrait; margin: 10mm; }

    /* Ukuran cetak dibuat besar agar mudah dibaca (pengguna lanjut usia),
       tetap muat di kertas A4 portrait */
    .kop h2 { font-size: 22px; }
    h1.judul { font-size: 20px; margin: 4px 0 12px; }
    .info td { font-size: 15px; padding: 2px 0; }
    .info td.l { width: 140px; }
    .ringkasan { gap: 6px; margin: 10px 0 12px; }
    .ringkasan .kotak { padding: 6px 8px; }
    .ringkasan .kotak .lbl { font-size: 12px; }
    .ringkasan .kotak .val { font-size: 17px; }
    .ringkasan .kotak .sub { font-size: 12px; }
    h3.sub-judul { font-size: 16px; margin: 12px 0 5px; }
    table.data th, table.data td { font-size: 14px; padding: 4px 5px; border-color: #555; }
    .badge { font-size: 11px; padding: 0 5px; }
    .dicetak { font-size: 11px; }
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