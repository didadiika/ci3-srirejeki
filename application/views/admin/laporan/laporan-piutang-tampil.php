<?php
/**
 * Laporan Piutang (invoice Selesai & Belum Lunas per pelanggan)
 * Variabel: $gaji (query invoice + pelanggan), $pelanggan
 */
$rows  = array();
$tot   = array('tonase' => 0, 'total' => 0, 'bayar' => 0, 'piutang' => 0);
$umur  = array('0-30' => 0, '31-60' => 0, '61-90' => 0, '>90' => 0);   // piutang per umur
$hari_ini = new DateTime(date('Y-m-d'));
$tertua = NULL;

foreach ($gaji->result() as $p) {
    $s = $this->db->query("select coalesce(sum(bayar),0) as bayar from invoice_bayar where id_invoice = ? and deleted_at is null", array($p->id_invoice))->row();
    $p->sudah_bayar = (int) $s->bayar;
    $p->piutang     = $p->total - $p->sudah_bayar;
    $p->umur        = $p->tanggal ? (int) $hari_ini->diff(new DateTime($p->tanggal))->days : 0;
    $rows[] = $p;

    $tot['tonase']  += $p->total_tonase;
    $tot['total']   += $p->total;
    $tot['bayar']   += $p->sudah_bayar;
    $tot['piutang'] += $p->piutang;

    if ($p->umur <= 30)      $umur['0-30']  += $p->piutang;
    else if ($p->umur <= 60) $umur['31-60'] += $p->piutang;
    else if ($p->umur <= 90) $umur['61-90'] += $p->piutang;
    else                     $umur['>90']   += $p->piutang;
    if ($tertua === NULL || $p->umur > $tertua) $tertua = $p->umur;
}

$this->load->view('admin/laporan/_laporan-head.php', array(
    'judul' => 'LAPORAN PIUTANG',
    'info'  => array(
        'Pelanggan' => '<strong>' . html_escape(isset($pelanggan) ? $pelanggan : '-') . '</strong>',
        'Per Tanggal' => tgl_indo(date('Y-m-d')),
    ),
));
?>

  <div class="ringkasan">
    <div class="kotak k-biru">
      <div class="lbl">Total Invoice</div>
      <div class="val"><?php echo rp_lap($tot['total']); ?></div>
      <div class="sub"><?php echo count($rows); ?> invoice belum lunas &middot; <?php echo rp_lap($tot['tonase']); ?> kg</div>
    </div>
    <div class="kotak k-hijau">
      <div class="lbl">Sudah Dibayar</div>
      <div class="val"><?php echo rp_lap($tot['bayar']); ?></div>
      <div class="sub"><?php echo persen_lap($tot['bayar'], $tot['total']); ?> dari total invoice</div>
    </div>
    <div class="kotak k-merah">
      <div class="lbl">Total Piutang</div>
      <div class="val"><?php echo rp_lap($tot['piutang']); ?></div>
      <div class="sub"><?php echo $tertua !== NULL ? 'Tertua ' . $tertua . ' hari' : '-'; ?></div>
    </div>
  </div>

<?php if (count($rows) == 0) { ?>

  <div class="kosong"><h3>Tidak ada piutang untuk pelanggan ini.</h3></div>

<?php } else { ?>

  <h3 class="sub-judul">Rincian Piutang</h3>
  <div class="scroll-x">
  <table class="data">
    <thead>
      <tr>
        <th style="width:35px">No</th>
        <th>Tanggal</th>
        <th>No Truk</th>
        <th>Tonase (Kg)</th>
        <th>Total Invoice</th>
        <th>Sudah Bayar</th>
        <th>Piutang</th>
        <th>Umur</th>
      </tr>
    </thead>
    <tbody>
    <?php $no = 0; foreach ($rows as $p) { $no++;
      $warna = $p->umur > 90 ? 'b-merah' : ($p->umur > 60 ? 'b-kuning' : ($p->umur > 30 ? 'b-abu' : 'b-hijau')); ?>
      <tr>
        <td class="c"><?php echo $no; ?></td>
        <td class="c nw"><?php echo tgl_db($p->tanggal); ?></td>
        <td class="nw"><?php echo html_escape($p->no_polisi); ?></td>
        <td class="r"><?php echo rp_lap($p->total_tonase); ?></td>
        <td class="r"><?php echo rp_lap($p->total); ?></td>
        <td class="r"><?php echo rp_lap($p->sudah_bayar); ?></td>
        <td class="r"><strong><?php echo rp_lap($p->piutang); ?></strong></td>
        <td class="c"><span class="badge <?php echo $warna; ?>"><?php echo $p->umur; ?> hari</span></td>
      </tr>
    <?php } ?>
    </tbody>
    <tfoot>
      <tr>
        <th colspan="3" style="text-align:left">TOTAL</th>
        <th class="r"><?php echo rp_lap($tot['tonase']); ?></th>
        <th class="r"><?php echo rp_lap($tot['total']); ?></th>
        <th class="r"><?php echo rp_lap($tot['bayar']); ?></th>
        <th class="r"><?php echo rp_lap($tot['piutang']); ?></th>
        <th></th>
      </tr>
    </tfoot>
  </table>
  </div>

  <h3 class="sub-judul">Piutang Berdasarkan Umur</h3>
  <table class="data" style="max-width:560px">
    <thead>
      <tr><th>Umur Piutang</th><th>Jumlah Piutang</th><th>Porsi</th></tr>
    </thead>
    <tbody>
      <?php foreach ($umur as $label => $nilai) { ?>
      <tr>
        <td><?php echo $label; ?> hari</td>
        <td class="r"><?php echo rp_lap($nilai); ?></td>
        <td class="r"><?php echo persen_lap($nilai, $tot['piutang']); ?></td>
      </tr>
      <?php } ?>
    </tbody>
  </table>

<?php } ?>

<?php $this->load->view('admin/laporan/_laporan-foot.php'); ?>
