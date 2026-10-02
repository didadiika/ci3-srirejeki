<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * View Surat Jalan PDF (dirender oleh Dompdf).
 * Gaya sama dengan invoice-cetak-pdf.php, TANPA harga, nominal, dan rekening.
 *
 * Variabel:
 *  $inv        object  baris invoice + pelanggan
 *  $items      array   baris invoice_d
 *  $total_qty  float   jumlah seluruh qty
 *  $nomor      string  nomor surat jalan
 *  $no_faktur  string  nomor faktur terkait
 *  $toko, $judul, $ttd, $satuan, $petugas
 */
$e = function ($s) { return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8'); };
$fmtQty = function ($q) {
    $q = (float) $q;
    return (floor($q) == $q) ? number_format($q, 0, ',', '.') : rtrim(rtrim(number_format($q, 2, ',', '.'), '0'), ',');
};
$lbl_qty = $satuan ? 'Qty (' . $satuan . ')' : 'Qty';
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title><?= $e($judul . ' ' . $nomor) ?></title>
<style>
    @page { margin: 28px 30px 40px 30px; }
    * { font-family: Helvetica, Arial, sans-serif; }
    body { font-size: 10.5px; color: #000; margin: 0; }
    table { border-collapse: collapse; width: 100%; }
    td, th { vertical-align: top; }

    .toko-nama   { font-size: 22px; font-weight: normal; margin: 0 0 4px 0; }
    .toko-info   { font-size: 11px; line-height: 1.35; }

    .box-judul td { border: 1px solid #000; padding: 2px 5px; font-size: 10.5px; }
    .box-judul .judul { font-weight: bold; font-size: 11px; }
    .text-right { text-align: right; }
    .text-center { text-align: center; }
    .bold { font-weight: bold; }

    .kepada { margin-top: 8px; font-size: 11px; }
    .kepada .nama { font-weight: bold; font-size: 12px; }

    table.items { margin-top: 8px; table-layout: fixed; }
    table.items th { border: 1px solid #000; padding: 3px 4px; font-weight: bold; text-align: center; background: #f2f2f2; }
    table.items td { border-left: 1px solid #000; border-right: 1px solid #000; padding: 2px 4px; }
    table.items thead { display: table-header-group; }
    table.items tr { page-break-inside: avoid; }
    table.items tr.sum td { border: 1px solid #000; padding: 3px 4px; }

    table.ttd td { text-align: center; padding-top: 6px; }
    .ttd-space { height: 48px; }

    .garis-bawah { margin-top: 14px; border-top: 1px solid #000; width: 100%; }
    .powered { text-align: right; font-size: 8px; font-weight: bold; margin-top: 6px; }
</style>
</head>
<body>

<!-- ========== HEADER ========== -->
<table>
    <tr>
        <td style="width:58%;">
            <div class="toko-nama"><?= $e($toko['nama']) ?></div>
            <div class="toko-info">
                <?= $e($toko['alamat']) ?><br>
                <?php if (!empty($toko['kota'])): ?><?= $e($toko['kota']) ?><br><?php endif; ?>
                <?= $e($toko['telepon']) ?>
            </div>
        </td>
        <td style="width:42%;">
            <table class="box-judul">
                <tr><td colspan="2" class="judul"><?= $e($judul) ?></td></tr>
                <tr><td style="width:40%;">Nomor #</td><td class="text-right"><?= $e($nomor) ?></td></tr>
                <tr><td>Tanggal</td><td class="text-right"><?= $e(tgl_indo($inv->tanggal)) ?></td></tr>
                <tr><td>No. Faktur</td><td class="text-right"><?= $e($no_faktur) ?></td></tr>
                <?php if (!empty($inv->no_polisi)): ?>
                <tr><td>No. Polisi</td><td class="text-right"><?= $e($inv->no_polisi) ?></td></tr>
                <?php endif; ?>
            </table>
        </td>
    </tr>
</table>

<div class="kepada">
    Kepada Yth:<br>
    <span class="nama"><?= $e(strtoupper($inv->nama_pelanggan)) ?></span>
    <?php if (!empty($inv->alamat)): ?><br><?= $e($inv->alamat) ?><?php endif; ?>
    <?php if (!empty($inv->telepon)): ?><br>Telp. <?= $e($inv->telepon) ?><?php endif; ?>
</div>

<!-- ========== DAFTAR BARANG (tanpa harga) ========== -->
<table class="items">
    <thead>
        <tr>
            <th style="width:5%;">No</th>
            <th style="width:12%;"><?= $e($lbl_qty) ?></th>
            <th style="width:58%;">Barang</th>
            <th style="width:25%;">Keterangan</th>
        </tr>
    </thead>
    <tbody>
    <?php if (empty($items)): ?>
        <tr><td colspan="4" class="text-center">Tidak ada barang</td></tr>
    <?php else: $no = 0; foreach ($items as $m): $no++; ?>
        <tr>
            <td><?= $no ?></td>
            <td class="text-center"><?= $fmtQty($m->qty) ?></td>
            <td><?= $e($m->nama_barang) ?></td>
            <td></td>
        </tr>
    <?php endforeach; endif; ?>
        <tr class="sum">
            <td class="bold text-right">Total</td>
            <td class="bold text-center"><?= $fmtQty($total_qty) ?></td>
            <td colspan="2"><?= $satuan ? $e($satuan) : '' ?></td>
        </tr>
    </tbody>
</table>

<!-- ========== TANDA TANGAN ========== -->
<table class="ttd" style="width:60%; page-break-inside: avoid;">
    <tr>
        <?php foreach ($ttd as $label): ?><td><?= $e($label) ?></td><?php endforeach; ?>
    </tr>
    <tr>
        <?php foreach ($ttd as $i => $label): ?>
            <td class="ttd-space" style="vertical-align: bottom;">
                <?= $i === 0 && $petugas ? '(' . $e(ucwords($petugas)) . ')' : '(....................)' ?>
            </td>
        <?php endforeach; ?>
    </tr>
</table>

<div class="powered">~Powered by MadaPOS</div>

<div class="garis-bawah"></div>

</body>
</html>
