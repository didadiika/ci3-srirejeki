<?php
/**
 * Laporan Pembelian
 * Variabel: $gaji (query pembelian), $sudah_bayar (query sum bayar), $lap, $kategori, $pengirim
 */
$rows = $gaji->result();

// Hitung total (rumus sama seperti sebelumnya: harga_satuan x total_tonase)
$tot_tonase = 0; $tot_beli = 0;
foreach ($rows as $p) {
    $tot_tonase += $p->total_tonase;
    $tot_beli   += $p->harga_satuan * $p->total_tonase;
}
$paid = 0;
foreach ($sudah_bayar->result() as $d) { $paid = (float) $d->paid; }
$utang = $tot_beli - $paid;

$this->load->view('admin/laporan/_laporan-head.php', array(
    'judul' => 'LAPORAN PEMBELIAN',
    'info'  => array(
        'Periode'  => '<strong>' . $lap['dari'] . ' s/d ' . $lap['sampai'] . '</strong>',
        'Kategori' => html_escape(isset($kategori) ? $kategori : '-'),
        'Pengirim' => html_escape(isset($pengirim) ? $pengirim : '-'),
    ),
));
?>

  <div class="ringkasan">
    <div class="kotak k-biru">
      <div class="lbl">Total Pembelian</div>
      <div class="val"><?php echo rp_lap($tot_beli); ?></div>
      <div class="sub"><?php echo count($rows); ?> transaksi &middot; <?php echo rp_lap($tot_tonase); ?> kg</div>
    </div>
    <div class="kotak k-hijau">
      <div class="lbl">Sudah Bayar (DP)</div>
      <div class="val"><?php echo rp_lap($paid); ?></div>
      <div class="sub"><?php echo persen_lap($paid, $tot_beli); ?> dari total pembelian</div>
    </div>
    <div class="kotak k-merah">
      <div class="lbl">Utang</div>
      <div class="val"><?php echo rp_lap($utang); ?></div>
      <div class="sub">Sisa yang belum dibayar</div>
    </div>
  </div>

<?php if (count($rows) == 0) { ?>

  <div class="kosong"><h3>Transaksi tidak ditemukan.</h3></div>

<?php } else { ?>

  <h3 class="sub-judul">Rincian Pembelian</h3>
  <div class="scroll-x">
  <table class="data">
    <thead>
      <tr>
        <th style="width:35px">No</th>
        <th>Tanggal</th>
        <th>No Truk</th>
        <th>Pengirim</th>
        <th>Kategori</th>
        <th>Harga Satuan</th>
        <th>Tonase (Kg)</th>
        <th>Total Beli</th>
      </tr>
    </thead>
    <tbody>
    <?php $no = 0; foreach ($rows as $p) { $no++; ?>
      <tr>
        <td class="c"><?php echo $no; ?></td>
        <td class="c nw"><?php echo tgl_db($p->tanggal); ?></td>
        <td class="nw"><?php echo html_escape($p->no_polisi); ?></td>
        <td><?php echo html_escape($p->nama_pengirim); ?></td>
        <td><?php echo html_escape($p->nama_kategori); ?></td>
        <td class="r"><?php echo rp_lap($p->harga_satuan); ?></td>
        <td class="r"><?php echo rp_lap($p->total_tonase); ?></td>
        <td class="r"><?php echo rp_lap($p->harga_satuan * $p->total_tonase); ?></td>
      </tr>
    <?php } ?>
    </tbody>
    <tfoot>
      <tr>
        <th colspan="6" style="text-align:left">TOTAL</th>
        <th class="r"><?php echo rp_lap($tot_tonase); ?></th>
        <th class="r"><?php echo rp_lap($tot_beli); ?></th>
      </tr>
      <tr>
        <th colspan="7" style="text-align:left">Sudah Bayar (DP)</th>
        <th class="r masuk"><?php echo rp_lap($paid); ?></th>
      </tr>
      <tr>
        <th colspan="7" style="text-align:left">Utang</th>
        <th class="r keluar"><?php echo rp_lap($utang); ?></th>
      </tr>
    </tfoot>
  </table>
  </div>

<?php } ?>

<?php $this->load->view('admin/laporan/_laporan-foot.php'); ?>
