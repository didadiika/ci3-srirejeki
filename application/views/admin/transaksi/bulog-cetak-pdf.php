<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * PDF Rekap Timbangan Bulog (dirender oleh Dompdf; layout memakai <table>, tanpa flex/grid).
 *
 * Variabel:
 *  $bulog   object  baris bulogs (+ jumlah_truk, jumlah_timbang)
 *  $trucks  array   baris bulog_orders, masing-masing punya ->weights (urut created_at ASC)
 *  $nomor, $toko, $judul, $kolom, $ttd, $petugas
 */
$e  = function ($s) { return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8'); };
$kg = function ($n) { return number_format((int) $n, 0, ',', '.'); };

$total_berat   = 0;
$total_timbang = 0;
foreach ($trucks as $t) { $total_berat += (int) $t->weight_by_truck; $total_timbang += count($t->weights); }
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
    .text-right { text-align: right; }
    .text-center { text-align: center; }
    .bold { font-weight: bold; }
    .muted { color: #666; }

    .toko-nama { font-size: 22px; margin: 0 0 4px 0; }
    .toko-info { font-size: 11px; line-height: 1.35; }
    .box-judul td { border: 1px solid #000; padding: 2px 5px; font-size: 10.5px; }
    .box-judul .judul { font-weight: bold; font-size: 11px; text-align: center; background: #f2f2f2; }

    table.ringkas { margin-top: 12px; }
    table.ringkas td { border: 1px solid #000; padding: 5px 8px; width: 33.33%; }
    table.ringkas .lbl { font-size: 9px; color: #444; text-transform: uppercase; }
    table.ringkas .val { font-size: 16px; font-weight: bold; margin-top: 2px; }

    h3.bagian { font-size: 11.5px; margin: 16px 0 5px 0; padding-bottom: 2px; border-bottom: 1.5px solid #000; }

    table.rekap th { border: 1px solid #000; padding: 3px 4px; background: #f2f2f2; text-align: center; }
    table.rekap td { border: 1px solid #000; padding: 3px 4px; }
    table.rekap tr.total td { font-weight: bold; background: #f7f7f7; }
    table.rekap thead { display: table-header-group; }
    table.rekap tr { page-break-inside: avoid; }

    table.truk { margin-top: 9px; table-layout: fixed; page-break-inside: avoid; }
    table.truk td.kepala { border: 1px solid #000; background: #eaeaea; padding: 4px 6px; font-size: 11px; }
    table.truk td.sel { border: 1px solid #888; padding: 1px 3px 3px 3px; text-align: center; height: 24px; }
    table.truk td.sel .no { font-size: 7px; color: #777; text-align: left; display: block; }
    table.truk td.sel .angka { font-size: 11px; font-weight: bold; }
    table.truk td.kosong { border: 1px solid #ccc; }
    table.truk td.sub { border: 1px solid #000; padding: 3px 6px; font-weight: bold; background: #f7f7f7; }
    table.truk td.belum { border: 1px solid #888; padding: 6px; font-style: italic; color: #666; text-align: center; }

    table.ttd { margin-top: 26px; page-break-inside: avoid; }
    table.ttd td { text-align: center; width: 33.33%; }
    .ttd-space { height: 52px; }
    .dicetak { margin-top: 14px; font-size: 8px; color: #666; border-top: 1px solid #999; padding-top: 3px; }
</style>
</head>
<body>

<!-- ========== HEADER ========== -->
<table>
    <tr>
        <td style="width:56%;">
            <div class="toko-nama"><?= $e($toko['nama']) ?></div>
            <div class="toko-info">
                <?= $e($toko['alamat']) ?><?= !empty($toko['kota']) ? ', ' . $e($toko['kota']) : '' ?><br>
                <?php if (!empty($toko['telepon'])): ?>Telp. <?= $e($toko['telepon']) ?><?php endif; ?>
            </div>
        </td>
        <td style="width:44%;">
            <table class="box-judul">
                <tr><td colspan="2" class="judul"><?= $e($judul) ?></td></tr>
                <tr><td style="width:40%;">No.</td><td class="bold"><?= $e($nomor) ?></td></tr>
                <tr><td>Tanggal Bulog</td><td class="bold"><?= $e(tgl_indo($bulog->date)) ?></td></tr>
                <tr><td>Dicetak</td><td><?= $e(date('d-m-Y H:i')) ?></td></tr>
            </table>
        </td>
    </tr>
</table>

<!-- ========== RINGKASAN ========== -->
<table class="ringkas">
    <tr>
        <td>
            <div class="lbl">Total Berat Bulog</div>
            <div class="val"><?= $kg($total_berat) ?> kg</div>
        </td>
        <td>
            <div class="lbl">Jumlah Truk</div>
            <div class="val"><?= count($trucks) ?> truk</div>
        </td>
        <td>
            <div class="lbl">Jumlah Timbang</div>
            <div class="val"><?= $total_timbang ?> kali</div>
        </td>
    </tr>
</table>

<?php if (empty($trucks)): ?>

    <p class="text-center muted" style="margin-top:30px;"><i>Belum ada truk pada Bulog ini.</i></p>

<?php else: ?>

<!-- ========== A. REKAP PER TRUK ========== -->
<h3 class="bagian">A. REKAP TONASE PER TRUK</h3>
<table class="rekap">
    <thead>
        <tr>
            <th style="width:5%;">No</th>
            <th style="width:20%;">No Truk</th>
            <th>Nama</th>
            <th style="width:18%;">Kota</th>
            <th style="width:13%;">Jml Timbang</th>
            <th style="width:17%;">Berat (kg)</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($trucks as $i => $t): ?>
        <tr>
            <td class="text-center"><?= $i + 1 ?></td>
            <td class="bold"><?= $e($t->truck_number) ?></td>
            <td><?= $t->name !== NULL && $t->name !== '' ? $e($t->name) : '-' ?></td>
            <td><?= $e($t->city) ?></td>
            <td class="text-center"><?= count($t->weights) ?></td>
            <td class="text-right"><?= $kg($t->weight_by_truck) ?></td>
        </tr>
    <?php endforeach; ?>
        <tr class="total">
            <td colspan="4">TOTAL</td>
            <td class="text-center"><?= $total_timbang ?></td>
            <td class="text-right"><?= $kg($total_berat) ?></td>
        </tr>
    </tbody>
</table>

<!-- ========== B. RINCIAN TIMBANGAN PER TRUK ========== -->
<h3 class="bagian">B. RINCIAN TIMBANGAN PER TRUK</h3>
<?php foreach ($trucks as $i => $t):
    $ws = $t->weights; ?>
<table class="truk">
    <tr>
        <td class="kepala" colspan="<?= $kolom ?>">
            <table>
                <tr>
                    <td class="bold"><?= $i + 1 ?>. <?= $e($t->truck_number)
                        . (($t->name !== NULL && $t->name !== '') ? ' &nbsp;&middot;&nbsp; ' . $e($t->name) : '')
                        . ' &nbsp;&middot;&nbsp; ' . $e($t->city) ?></td>
                    <td class="text-right bold" style="width:40%;">
                        <?= count($ws) ?>x timbang &nbsp;|&nbsp; <?= $kg($t->weight_by_truck) ?> kg
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <?php if (empty($ws)): ?>
    <tr><td class="belum" colspan="<?= $kolom ?>">Belum ada timbangan untuk truk ini.</td></tr>
    <?php else:
        foreach (array_chunk($ws, $kolom, true) as $baris): ?>
    <tr>
        <?php foreach ($baris as $j => $w): ?>
        <td class="sel"><span class="no"><?= $j + 1 ?></span><span class="angka"><?= $kg($w->weight) ?></span></td>
        <?php endforeach; ?>
        <?php for ($k = count($baris); $k < $kolom; $k++): ?><td class="kosong"></td><?php endfor; ?>
    </tr>
    <?php endforeach; ?>
    <tr>
        <td class="sub" colspan="<?= $kolom ?>">
            <table><tr>
                <td>Subtotal <?= $e($t->truck_number) ?> (<?= count($ws) ?> kali timbang)</td>
                <td class="text-right" style="width:30%;"><?= $kg($t->weight_by_truck) ?> kg</td>
            </tr></table>
        </td>
    </tr>
    <?php endif; ?>
</table>
<?php endforeach; ?>

<?php endif; ?>

<!-- ========== TANDA TANGAN ========== -->
<table class="ttd">
    <tr>
        <?php foreach ($ttd as $label): ?><td><?= $e($label) ?>,</td><?php endforeach; ?>
    </tr>
    <tr>
        <?php foreach ($ttd as $label): ?><td class="ttd-space"></td><?php endforeach; ?>
    </tr>
    <tr>
        <?php foreach ($ttd as $label): ?><td>( ____________________ )</td><?php endforeach; ?>
    </tr>
</table>

<div class="dicetak">Dicetak oleh <?= $e($petugas) ?> pada <?= $e(date('d-m-Y H:i:s')) ?></div>

</body>
</html>
