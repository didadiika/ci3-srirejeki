<?php
/**
 * Hasil Laporan Biaya Invoice
 * Variabel: $invoices, $grand, $per_nama, $jenis, $status, $periode, $kategori, $pelanggan
 */
$this->load->view('admin/laporan/_laporan-head.php', array(
    'judul' => 'LAPORAN BIAYA INVOICE',
    'info'  => array(
        'Periode Invoice' => '<strong>' . $periode . '</strong>',
        'Kategori'        => html_escape($kategori),
        'Pelanggan'       => html_escape($pelanggan),
        'Status Bayar'    => html_escape($status),
    ),
));
?>

  <div class="ringkasan">
    <div class="kotak k-biru">
      <div class="lbl">Total Biaya Invoice</div>
      <div class="val"><?php echo rp_lap($grand['bill']); ?></div>
      <div class="sub"><?php echo $grand['biaya']; ?> biaya dari <?php echo $grand['invoice']; ?> invoice</div>
    </div>
    <div class="kotak k-hijau">
      <div class="lbl">Sudah Terbayar</div>
      <div class="val"><?php echo rp_lap($grand['paid']); ?></div>
      <div class="sub"><?php echo $grand['bill'] > 0 ? number_format($grand['paid'] / $grand['bill'] * 100, 1, ',', '.') : '0'; ?>% &middot; <?php echo $grand['inv_lunas']; ?> invoice lunas</div>
    </div>
    <div class="kotak k-merah">
      <div class="lbl">Belum Terbayar</div>
      <div class="val"><?php echo rp_lap($grand['sisa']); ?></div>
      <div class="sub"><?php echo $grand['inv_belum']; ?> invoice belum lunas</div>
    </div>
  </div>

<?php if (empty($invoices)) { ?>

  <div class="kosong"><h3>Tidak ada biaya invoice pada periode / filter ini.</h3></div>

<?php } else { ?>

  <?php if ($jenis == "Ringkas") { ?>
  <!-- ======================= RINGKAS: PER INVOICE ======================= -->
  <h3 class="sub-judul">Rincian per Invoice</h3>
  <table class="data">
    <thead>
      <tr>
        <th style="width:35px">No</th>
        <th>Tanggal</th>
        <th>Pelanggan</th>
        <th>No Truk</th>
        <th>Kategori</th>
        <th>Jml Biaya</th>
        <th>Total Biaya</th>
        <th>Terbayar</th>
        <th>Belum Terbayar</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
    <?php $no = 0; foreach ($invoices as $inv) { $no++;
      $badge = $inv['lunas'] ? '<span class="badge b-hijau">Lunas</span>'
             : ($inv['total_paid'] > 0 ? '<span class="badge b-kuning">Sebagian</span>' : '<span class="badge b-merah">Belum Bayar</span>'); ?>
      <tr>
        <td class="c"><?php echo $no; ?></td>
        <td class="c"><?php echo tgl_db($inv['tanggal']); ?></td>
        <td><?php echo html_escape($inv['nama_pelanggan']); ?></td>
        <td><?php echo html_escape($inv['no_polisi']); ?></td>
        <td><?php echo html_escape($inv['nama_kategori']); ?></td>
        <td class="c"><?php echo count($inv['biaya']); ?></td>
        <td class="r"><?php echo rp_lap($inv['total_bill']); ?></td>
        <td class="r"><?php echo rp_lap($inv['total_paid']); ?></td>
        <td class="r"><?php echo rp_lap($inv['sisa']); ?></td>
        <td class="c"><?php echo $badge; ?></td>
      </tr>
    <?php } ?>
    </tbody>
    <tfoot>
      <tr>
        <th colspan="5" style="text-align:left">TOTAL</th>
        <th class="c"><?php echo $grand['biaya']; ?></th>
        <th class="r"><?php echo rp_lap($grand['bill']); ?></th>
        <th class="r"><?php echo rp_lap($grand['paid']); ?></th>
        <th class="r"><?php echo rp_lap($grand['sisa']); ?></th>
        <th></th>
      </tr>
    </tfoot>
  </table>

  <?php } else { ?>
  <!-- ======================= RINCI: PER BIAYA ======================= -->
  <h3 class="sub-judul">Rincian per Biaya</h3>
  <table class="data">
    <thead>
      <tr>
        <th style="width:35px">No</th>
        <th>Nama Biaya</th>
        <th>Nominal</th>
        <th>Terbayar</th>
        <th>Belum Terbayar</th>
        <th>Bayar Terakhir</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
    <?php $no = 0; foreach ($invoices as $inv) { $no++; ?>
      <tr class="grup">
        <td class="c"><?php echo $no; ?></td>
        <td colspan="6">
          <?php echo tgl_db($inv['tanggal']); ?> &nbsp;|&nbsp; <?php echo html_escape($inv['nama_pelanggan']); ?>
          &nbsp;|&nbsp; <?php echo html_escape($inv['no_polisi']); ?>
          &nbsp;|&nbsp; <?php echo html_escape($inv['nama_kategori']); ?>
        </td>
      </tr>
      <?php foreach ($inv['biaya'] as $b) {
        $lunas = round($b['sisa'], 2) <= 0;
        $badge = $lunas ? '<span class="badge b-hijau">Lunas</span>'
               : ($b['paid'] > 0 ? '<span class="badge b-kuning">Sebagian</span>' : '<span class="badge b-merah">Belum Bayar</span>'); ?>
      <tr>
        <td></td>
        <td><?php echo html_escape($b['bill_name']); ?></td>
        <td class="r"><?php echo rp_lap($b['bill']); ?></td>
        <td class="r"><?php echo rp_lap($b['paid']); ?></td>
        <td class="r"><?php echo rp_lap($b['sisa']); ?></td>
        <td class="c"><?php echo $b['last_paid'] ? tgl_db($b['last_paid']) : '-'; ?></td>
        <td class="c"><?php echo $badge; ?></td>
      </tr>
      <?php } ?>
      <tr class="subtotal">
        <td></td>
        <td>Subtotal invoice</td>
        <td class="r"><?php echo rp_lap($inv['total_bill']); ?></td>
        <td class="r"><?php echo rp_lap($inv['total_paid']); ?></td>
        <td class="r"><?php echo rp_lap($inv['sisa']); ?></td>
        <td colspan="2"></td>
      </tr>
    <?php } ?>
    </tbody>
    <tfoot>
      <tr>
        <th colspan="2" style="text-align:left">GRAND TOTAL</th>
        <th class="r"><?php echo rp_lap($grand['bill']); ?></th>
        <th class="r"><?php echo rp_lap($grand['paid']); ?></th>
        <th class="r"><?php echo rp_lap($grand['sisa']); ?></th>
        <th colspan="2"></th>
      </tr>
    </tfoot>
  </table>
  <?php } ?>

  <!-- ======================= REKAP PER NAMA BIAYA ======================= -->
  <h3 class="sub-judul">Rekap per Jenis Biaya</h3>
  <table class="data" style="max-width:720px">
    <thead>
      <tr>
        <th style="width:35px">No</th>
        <th>Nama Biaya</th>
        <th>Jumlah</th>
        <th>Total</th>
        <th>Terbayar</th>
        <th>Belum Terbayar</th>
      </tr>
    </thead>
    <tbody>
    <?php $no = 0; foreach ($per_nama as $pn) { $no++; ?>
      <tr>
        <td class="c"><?php echo $no; ?></td>
        <td><?php echo html_escape($pn['bill_name']); ?></td>
        <td class="c"><?php echo $pn['jumlah']; ?></td>
        <td class="r"><?php echo rp_lap($pn['bill']); ?></td>
        <td class="r"><?php echo rp_lap($pn['paid']); ?></td>
        <td class="r"><?php echo rp_lap($pn['sisa']); ?></td>
      </tr>
    <?php } ?>
    </tbody>
  </table>

<?php } ?>

<?php $this->load->view('admin/laporan/_laporan-foot.php'); ?>
