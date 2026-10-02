<?php
/**
 * Laporan Penjualan - Tampil Rinci (per barang)
 * Variabel: $gaji (query invoice + pelanggan + invoice_d), $jenis, $lap, $kategori, $pelanggan
 */
// Kelompokkan baris barang per invoice (urutan dari query dipertahankan)
$grup = array();
$tot  = array('qty' => 0, 'sub' => 0, 'baris' => 0);
foreach ($gaji->result() as $p) {
    if (!isset($grup[$p->id_invoice])) {
        $grup[$p->id_invoice] = array(
            'tanggal'   => $p->tanggal,
            'pelanggan' => $p->nama_pelanggan,
            'no_polisi' => $p->no_polisi,
            'items'     => array(),
            'qty'       => 0,
            'sub'       => 0,
        );
    }
    $grup[$p->id_invoice]['items'][] = $p;
    $grup[$p->id_invoice]['qty']    += $p->qty;
    $grup[$p->id_invoice]['sub']    += $p->sub_total;
    $tot['qty']   += $p->qty;
    $tot['sub']   += $p->sub_total;
    $tot['baris']++;
}

$this->load->view('admin/laporan/_laporan-head.php', array(
    'judul' => 'LAPORAN PENJUALAN',
    'info'  => array(
        'Jenis Laporan' => html_escape($jenis),
        'Periode'       => '<strong>' . $lap['dari'] . ' s/d ' . $lap['sampai'] . '</strong>',
        'Kategori'      => html_escape(isset($kategori) ? $kategori : '-'),
        'Pelanggan'     => html_escape(isset($pelanggan) ? $pelanggan : '-'),
    ),
));
?>

  <div class="ringkasan">
    <div class="kotak k-biru">
      <div class="lbl">Total Penjualan</div>
      <div class="val"><?php echo rp_lap($tot['sub']); ?></div>
      <div class="sub"><?php echo count($grup); ?> invoice &middot; <?php echo $tot['baris']; ?> baris barang</div>
    </div>
    <div class="kotak k-hijau">
      <div class="lbl">Total Tonase</div>
      <div class="val"><?php echo rp_lap($tot['qty']); ?> kg</div>
      <div class="sub">Rata-rata harga <?php echo $tot['qty'] > 0 ? rp_lap(round($tot['sub'] / $tot['qty'], 2)) : 0; ?> / kg</div>
    </div>
    <div class="kotak k-abu">
      <div class="lbl">Rata-rata per Invoice</div>
      <div class="val"><?php echo count($grup) > 0 ? rp_lap(round($tot['sub'] / count($grup))) : 0; ?></div>
      <div class="sub"><?php echo count($grup) > 0 ? rp_lap(round($tot['qty'] / count($grup))) : 0; ?> kg per invoice</div>
    </div>
  </div>

<?php if (empty($grup)) { ?>

  <div class="kosong"><h3>Transaksi tidak ditemukan.</h3></div>

<?php } else { ?>

  <h3 class="sub-judul">Rincian Barang per Invoice</h3>
  <div class="scroll-x">
  <table class="data">
    <thead>
      <tr>
        <th style="width:35px">No</th>
        <th>Barang</th>
        <th>Harga</th>
        <th>Tonase (Kg)</th>
        <th>Sub Total</th>
      </tr>
    </thead>
    <tbody>
    <?php $no = 0; foreach ($grup as $g) { $no++; ?>
      <tr class="grup">
        <td class="c"><?php echo $no; ?></td>
        <td colspan="4">
          <?php echo tgl_db($g['tanggal']); ?> &nbsp;|&nbsp; <?php echo html_escape($g['pelanggan']); ?>
          <?php if ($g['no_polisi']) { ?>&nbsp;|&nbsp; <?php echo html_escape($g['no_polisi']); ?><?php } ?>
        </td>
      </tr>
      <?php foreach ($g['items'] as $it) { ?>
      <tr>
        <td></td>
        <td><?php echo html_escape($it->nama_barang); ?></td>
        <td class="r"><?php echo rp_lap($it->harga); ?></td>
        <td class="r"><?php echo rp_lap($it->qty); ?></td>
        <td class="r"><?php echo rp_lap($it->sub_total); ?></td>
      </tr>
      <?php } ?>
      <?php if (count($g['items']) > 1) { ?>
      <tr class="subtotal">
        <td></td>
        <td colspan="2">Subtotal invoice</td>
        <td class="r"><?php echo rp_lap($g['qty']); ?></td>
        <td class="r"><?php echo rp_lap($g['sub']); ?></td>
      </tr>
      <?php } ?>
    <?php } ?>
    </tbody>
    <tfoot>
      <tr>
        <th colspan="3" style="text-align:left">GRAND TOTAL</th>
        <th class="r"><?php echo rp_lap($tot['qty']); ?></th>
        <th class="r"><?php echo rp_lap($tot['sub']); ?></th>
      </tr>
    </tfoot>
  </table>
  </div>

<?php } ?>

<?php $this->load->view('admin/laporan/_laporan-foot.php'); ?>
