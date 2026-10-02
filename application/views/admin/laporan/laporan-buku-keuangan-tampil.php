<?php
/**
 * Laporan Buku Keuangan
 * Variabel: $gaji (query transaksi), $saldo (saldo awal sebelum periode), $lap
 */
$rows       = $gaji->result();
$saldo_awal = (float) $saldo;
$tot_debit  = 0;
$tot_kredit = 0;
foreach ($rows as $p) {
    $tot_debit  += $p->debit;
    $tot_kredit += $p->kredit;
}
$saldo_akhir = $saldo_awal + $tot_debit - $tot_kredit;
$selisih     = $tot_debit - $tot_kredit;

$this->load->view('admin/laporan/_laporan-head.php', array(
    'judul' => 'LAPORAN BUKU KEUANGAN',
    'info'  => array(
        'Periode' => '<strong>' . $lap['dari'] . ' s/d ' . $lap['sampai'] . '</strong>',
    ),
));
?>

  <div class="ringkasan">
    <div class="kotak k-abu">
      <div class="lbl">Saldo Awal</div>
      <div class="val"><?php echo rp_lap($saldo_awal); ?></div>
      <div class="sub">Sebelum <?php echo $lap['dari']; ?></div>
    </div>
    <div class="kotak k-hijau">
      <div class="lbl">Total Debit (Masuk)</div>
      <div class="val"><?php echo rp_lap($tot_debit); ?></div>
      <div class="sub"><?php $n = 0; foreach ($rows as $p) if ($p->debit > 0) $n++; echo $n; ?> transaksi</div>
    </div>
    <div class="kotak k-merah">
      <div class="lbl">Total Kredit (Keluar)</div>
      <div class="val"><?php echo rp_lap($tot_kredit); ?></div>
      <div class="sub"><?php $n = 0; foreach ($rows as $p) if ($p->kredit > 0) $n++; echo $n; ?> transaksi</div>
    </div>
    <div class="kotak k-biru">
      <div class="lbl">Saldo Akhir</div>
      <div class="val"><?php echo rp_lap($saldo_akhir); ?></div>
      <div class="sub"><?php echo ($selisih >= 0 ? 'Naik ' : 'Turun ') . rp_lap(abs($selisih)); ?> di periode ini</div>
    </div>
  </div>

  <h3 class="sub-judul">Rincian Transaksi</h3>
  <div class="scroll-x">
  <table class="data">
    <thead>
      <tr>
        <th style="width:35px">No</th>
        <th>Tanggal</th>
        <th>Keterangan</th>
        <th>Jenis</th>
        <th>Debit</th>
        <th>Kredit</th>
        <th>Saldo</th>
      </tr>
    </thead>
    <tbody>
      <tr class="saldo">
        <td></td>
        <td colspan="5">Saldo Awal</td>
        <td class="r"><?php echo rp_lap($saldo_awal); ?></td>
      </tr>
    <?php if (count($rows) == 0) { ?>
      <tr><td colspan="7" class="c" style="padding:20px;color:#777">Tidak ada transaksi pada periode ini.</td></tr>
    <?php } ?>
    <?php $no = 0; $berjalan = $saldo_awal; foreach ($rows as $p) { $no++;
      $berjalan = $berjalan + $p->debit - $p->kredit; ?>
      <tr>
        <td class="c"><?php echo $no; ?></td>
        <td class="nw"><?php echo tgl_indo($p->tanggal); ?></td>
        <td><?php echo html_escape($p->keterangan); ?></td>
        <td class="c nw"><?php echo html_escape($p->jenis); ?></td>
        <td class="r <?php echo $p->debit > 0 ? 'masuk' : 'redup'; ?>"><?php echo $p->debit > 0 ? rp_lap($p->debit) : '-'; ?></td>
        <td class="r <?php echo $p->kredit > 0 ? 'keluar' : 'redup'; ?>"><?php echo $p->kredit > 0 ? rp_lap($p->kredit) : '-'; ?></td>
        <td class="r <?php echo $berjalan < 0 ? 'keluar' : ''; ?>"><?php echo rp_lap($berjalan); ?></td>
      </tr>
    <?php } ?>
    </tbody>
    <tfoot>
      <tr>
        <th colspan="4" style="text-align:left">TOTAL</th>
        <th class="r masuk"><?php echo rp_lap($tot_debit); ?></th>
        <th class="r keluar"><?php echo rp_lap($tot_kredit); ?></th>
        <th class="r"><?php echo rp_lap($saldo_akhir); ?></th>
      </tr>
    </tfoot>
  </table>
  </div>

<?php $this->load->view('admin/laporan/_laporan-foot.php'); ?>
