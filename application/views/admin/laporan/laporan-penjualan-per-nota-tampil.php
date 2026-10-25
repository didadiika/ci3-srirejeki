<?php
/**
 * Laporan Penjualan - Tampil per Nota
 * Variabel: $gaji (query invoice + pelanggan), $jenis, $lap, $kategori, $pelanggan, $demands_of_bulog, $list_bulog
 * Kategori IR: tambah kolom Retur & Tonase Akhir setelah Tonase, plus keterangan pemenuhan kebutuhan Bulog:
 *   Total kebutuhan = demands_of_bulog (mis. 20.900) x jumlah permintaan di tabel bulogs pada periode laporan
 *   Selisih         = total kebutuhan - total tonase bersih (tonase akhir)  -> kurang / sisa tonase
 */
$is_ir = (isset($kategori) && strtoupper(trim($kategori)) === 'IR');
$kebutuhan_bulog = isset($demands_of_bulog) ? (float) $demands_of_bulog : 0;   // kebutuhan per permintaan
$list_bulog      = isset($list_bulog) ? $list_bulog : array();
$jml_permintaan  = count($list_bulog);
$total_kebutuhan = $kebutuhan_bulog * $jml_permintaan;

$rows = array();
$tot = array('tonase' => 0, 'retur' => 0, 'tonase_akhir' => 0, 'total' => 0, 'bayar' => 0, 'piutang' => 0, 'lunas' => 0);
foreach ($gaji->result() as $p) {
    // Akumulasi pembayaran per invoice (sama seperti sebelumnya)
    $s = $this->db->query("select coalesce(sum(bayar),0) as bayar from invoice_bayar where id_invoice = ? and deleted_at is null", array($p->id_invoice))->row();
    $p->sudah_bayar = (int) $s->bayar;
    $p->piutang     = $p->total - $p->sudah_bayar;
    $p->retur        = isset($p->tonase_return) ? (float) $p->tonase_return : 0;
    $p->tonase_akhir = (float) $p->total_tonase - $p->retur;
    $rows[] = $p;

    $tot['tonase']       += $p->total_tonase;
    $tot['retur']        += $p->retur;
    $tot['tonase_akhir'] += $p->tonase_akhir;
    $tot['total']   += $p->total;
    $tot['bayar']   += $p->sudah_bayar;
    $tot['piutang'] += $p->piutang;
    if ($p->piutang <= 0) $tot['lunas']++;
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
      <div class="val"><?php echo rp_lap($tot['total']); ?></div>
      <div class="sub"><?php echo count($rows); ?> invoice &middot; <?php echo rp_lap($tot['tonase']); ?> kg</div>
    </div>
    <div class="kotak k-hijau">
      <div class="lbl">Sudah Dibayar</div>
      <div class="val"><?php echo rp_lap($tot['bayar']); ?></div>
      <div class="sub"><?php echo persen_lap($tot['bayar'], $tot['total']); ?> &middot; <?php echo $tot['lunas']; ?> invoice lunas</div>
    </div>
    <div class="kotak k-merah">
      <div class="lbl">Piutang</div>
      <div class="val"><?php echo rp_lap($tot['piutang']); ?></div>
      <div class="sub"><?php echo count($rows) - $tot['lunas']; ?> invoice belum lunas</div>
    </div>
  </div>

<style>
  .ket-bulog { margin-top: 10px; padding: 8px 12px; border: 1px solid #ccc; border-left: 4px solid #f39c12; border-radius: 4px; font-size: 17px; }
  table.data.bulog { width: auto; min-width: 320px; }
</style>

<?php if (count($rows) == 0) { ?>

  <div class="kosong"><h3>Transaksi tidak ditemukan.</h3></div>

<?php } else { ?>

  <h3 class="sub-judul">Rincian per Invoice</h3>
  <div class="scroll-x">
  <table class="data">
    <thead>
      <tr>
        <th style="width:35px">No</th>
        <th>Tanggal</th>
        <th>Pelanggan</th>
        <th>No Truk</th>
        <th>Tonase (Kg)</th>
        <?php if ($is_ir) { ?>
        <th>Retur (Kg)</th>
        <th>Tonase Akhir (Kg)</th>
        <?php } ?>
        <th>Total Invoice</th>
        <th>Sudah Bayar</th>
        <th>Piutang</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
    <?php $no = 0; foreach ($rows as $p) { $no++;
      $badge = $p->piutang <= 0 ? '<span class="badge b-hijau">Lunas</span>'
             : ($p->sudah_bayar > 0 ? '<span class="badge b-kuning">Sebagian</span>' : '<span class="badge b-merah">Belum Bayar</span>'); ?>
      <tr>
        <td class="c"><?php echo $no; ?></td>
        <td class="c nw"><?php echo tgl_db($p->tanggal); ?></td>
        <td><?php echo html_escape($p->nama_pelanggan); ?></td>
        <td class="nw"><?php echo html_escape($p->no_polisi); ?></td>
        <td class="r"><?php echo rp_lap($p->total_tonase); ?></td>
        <?php if ($is_ir) { ?>
        <td class="r"><?php echo $p->retur > 0 ? '<span class="keluar">' . rp_lap($p->retur) . '</span>' : '0'; ?></td>
        <td class="r"><strong><?php echo rp_lap($p->tonase_akhir); ?></strong></td>
        <?php } ?>
        <td class="r"><?php echo rp_lap($p->total); ?></td>
        <td class="r"><?php echo rp_lap($p->sudah_bayar); ?></td>
        <td class="r"><?php echo rp_lap($p->piutang); ?></td>
        <td class="c"><?php echo $badge; ?></td>
      </tr>
    <?php } ?>
    </tbody>
    <tfoot>
      <tr>
        <th colspan="4" style="text-align:left">TOTAL</th>
        <th class="r"><?php echo rp_lap($tot['tonase']); ?></th>
        <?php if ($is_ir) { ?>
        <th class="r"><?php echo rp_lap($tot['retur']); ?></th>
        <th class="r"><?php echo rp_lap($tot['tonase_akhir']); ?></th>
        <?php } ?>
        <th class="r"><?php echo rp_lap($tot['total']); ?></th>
        <th class="r"><?php echo rp_lap($tot['bayar']); ?></th>
        <th class="r"><?php echo rp_lap($tot['piutang']); ?></th>
        <th></th>
      </tr>
    </tfoot>
  </table>
  </div>

<?php } ?>

<?php if ($is_ir) {
    /* Pemenuhan kebutuhan Bulog: (kebutuhan x jumlah permintaan) - total tonase bersih */
    $selisih = $total_kebutuhan - $tot['tonase_akhir'];
    if ($kebutuhan_bulog <= 0) {
        $ket = '<span class="redup">Kebutuhan Bulog belum diatur di Setting Sistem.</span>';
    } else if ($jml_permintaan == 0) {
        $ket = '<span class="redup">(tidak ada permintaan Bulog pada periode ini)</span>';
    } else if ($selisih > 0) {
        $ket = '<span class="keluar">(kurang ' . rp_lap($selisih) . ' Kg)</span>';
    } else if ($selisih < 0) {
        $ket = '<span class="masuk">(sisa ' . rp_lap(abs($selisih)) . ' Kg)</span>';
    } else {
        $ket = '<span class="masuk">(terpenuhi)</span>';
    }
?>
  <div class="ket-bulog">
    <strong>Permintaan Bulog :</strong>
    (<?php echo rp_lap($kebutuhan_bulog); ?> x <?php echo $jml_permintaan; ?> = <?php echo rp_lap($total_kebutuhan); ?>
    / <?php echo rp_lap($tot['tonase_akhir']); ?>)
    <?php echo count($rows); ?> Transaksi <?php echo $ket; ?>
  </div>

  <h3 class="sub-judul">Daftar PO Bulog</h3>
  <?php if ($jml_permintaan == 0) { ?>
    <div class="redup">Tidak ada permintaan Bulog pada periode ini.</div>
  <?php } else { ?>
  <table class="data bulog">
    <thead>
      <tr>
        <th style="width:35px">No</th>
        <th>Tanggal</th>
      </tr>
    </thead>
    <tbody>
    <?php $nb = 0; foreach ($list_bulog as $b) { $nb++; ?>
      <tr>
        <td class="c"><?php echo $nb; ?></td>
        <td class="c nw"><?php echo tgl_db($b->date); ?></td>
      </tr>
    <?php } ?>
    </tbody>
  </table>
  <?php } ?>
<?php } ?>

<?php $this->load->view('admin/laporan/_laporan-foot.php'); ?>
