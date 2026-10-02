<?php
/**
 * Halaman Biaya Invoice
 * Variabel: $invoice (row invoice + pelanggan)
 */
$id_invoice = $invoice->id_invoice;
?>
<style>
  #tabel-biaya td, #tabel-biaya th { vertical-align: middle; }
  #tabel-biaya .aksi { white-space: nowrap; }
  #tabel-pembayaran td, #tabel-pembayaran th { vertical-align: middle; }
  .biaya-ringkas .info-box { min-height: 70px; margin-bottom: 10px; }
  .biaya-ringkas .info-box-icon { height: 70px; line-height: 70px; width: 70px; font-size: 30px; }
  .biaya-ringkas .info-box-content { margin-left: 70px; }
  .info-tagihan { background: #f9fafc; border: 1px solid #e3e7ee; border-radius: 3px; padding: 10px 12px; margin-bottom: 15px; }
  .info-tagihan .lbl { color: #777; font-size: 12px; display: block; }
  .info-tagihan .val { font-weight: 600; font-size: 15px; }
  .row-edit { background: #fcf8e3 !important; }
</style>

<section class="content-header">
  <h1>Biaya Invoice <small><?php echo html_escape($invoice->nama_pelanggan); ?></small></h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-refresh"></i> Transaksi</a></li>
    <li><a href="<?php echo base_url('transaksi/invoice'); ?>">Invoice</a></li>
    <li class="active">Biaya Invoice</li>
  </ol>
</section>

<section class="content">

  <!-- ======================= INFO INVOICE ======================= -->
  <div class="box box-warning">
    <div class="box-header with-border">
      <h3 class="box-title">Data Invoice</h3>
    </div>
    <div class="box-body">
      <div class="table-responsive">
        <table class="table table-condensed" style="margin-bottom:10px">
          <tr>
            <th style="width:15%">Tanggal</th>
            <td style="width:35%"><?php echo tgl_indo($invoice->tanggal); ?></td>
            <th style="width:15%">Pelanggan</th>
            <td><?php echo html_escape($invoice->nama_pelanggan); ?></td>
          </tr>
          <tr>
            <th>No Truk</th>
            <td><?php echo html_escape($invoice->no_polisi); ?></td>
            <th>Total Invoice</th>
            <td><strong><?php echo uang($invoice->total); ?></strong>
              &nbsp;<span class="badge <?php echo $invoice->status == 'Selesai' ? 'btn-primary' : 'btn-warning'; ?>"><?php echo html_escape($invoice->status); ?></span>
            </td>
          </tr>
        </table>
      </div>

      <div class="row biaya-ringkas">
        <div class="col-md-4 col-sm-4">
          <div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="fa fa-file-text-o"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Total Biaya</span>
              <span class="info-box-number" id="sum-bill">0</span>
            </div>
          </div>
        </div>
        <div class="col-md-4 col-sm-4">
          <div class="info-box">
            <span class="info-box-icon bg-green"><i class="fa fa-check"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Sudah Dibayar</span>
              <span class="info-box-number" id="sum-paid">0</span>
            </div>
          </div>
        </div>
        <div class="col-md-4 col-sm-4">
          <div class="info-box">
            <span class="info-box-icon bg-red"><i class="fa fa-hourglass-half"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Sisa Tagihan Biaya</span>
              <span class="info-box-number" id="sum-sisa">0</span>
            </div>
          </div>
        </div>
      </div>

      <a href="javascript:;" class="btn btn-app" id="btn-tambah-biaya"><i class="fa fa-plus"></i> Tambah Biaya</a>
      <a href="<?php echo base_url('transaksi/invoice/tambah-barang/' . $id_invoice); ?>" class="btn btn-app"><i class="fa fa-file-text-o"></i> Detail Invoice</a>
      <a href="<?php echo base_url('transaksi/invoice'); ?>" class="btn btn-app"><i class="fa fa-arrow-left"></i> Kembali</a>
    </div>
  </div>

  <!-- ======================= DAFTAR BIAYA ======================= -->
  <div class="box box-primary">
    <div class="box-header with-border">
      <h3 class="box-title">Daftar Biaya</h3>
    </div>
    <div class="box-body">
      <div id="notif-page"></div>

      <div class="callout callout-info" style="padding:8px 15px; margin-bottom:12px">
        <i class="fa fa-info-circle"></i> Biaya hanya dapat dihapus jika <strong>belum ada pembayaran</strong>.
        Hapus dulu seluruh pembayarannya bila ingin menghapus biaya.
      </div>

      <div class="table-responsive">
        <table class="table table-bordered table-striped" id="tabel-biaya">
          <thead>
            <tr>
              <th style="width:40px">No</th>
              <th>Nama Biaya</th>
              <th class="text-right">Nominal</th>
              <th class="text-right">Dibayar</th>
              <th class="text-right">Sisa</th>
              <th class="text-center">Status</th>
              <th class="text-center" style="width:260px">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr><td colspan="7" class="text-center text-muted"><i class="fa fa-spinner fa-spin"></i> Memuat data...</td></tr>
          </tbody>
          <tfoot>
            <tr>
              <th colspan="2">TOTAL</th>
              <th class="text-right" id="foot-bill">0</th>
              <th class="text-right" id="foot-paid">0</th>
              <th class="text-right" id="foot-sisa">0</th>
              <th colspan="2"></th>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
  </div>

</section>


<!-- ======================= MODAL TAMBAH / EDIT BIAYA ======================= -->
<div class="modal fade" id="modal-biaya" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="judul-modal-biaya">Tambah Biaya</h4>
      </div>
      <form id="form-biaya" action="<?php echo base_url('invoice_cost/simpan_biaya'); ?>" method="post" autocomplete="off">
        <div class="modal-body">
          <div id="notif-biaya"></div>
          <input type="hidden" name="id" value="">
          <input type="hidden" name="invoice_id" value="<?php echo $id_invoice; ?>">

          <div class="form-group">
            <label class="control-label">Nama Biaya</label>
            <input type="text" name="bill_name" class="form-control" placeholder="Contoh: Biaya Pengolahan" maxlength="255" required>
          </div>

          <div class="form-group">
            <label class="control-label">Nominal Biaya</label>
            <div class="input-group">
              <span class="input-group-addon">Rp</span>
              <input type="text" name="bill" id="input-bill" class="form-control text-right" placeholder="0" required>
            </div>
            <span class="help-block" id="help-bill"></span>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-times"></i> Batal</button>
          <button type="submit" class="btn btn-primary" id="btn-simpan-biaya"><i class="fa fa-save"></i> Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>


<!-- ======================= MODAL PEMBAYARAN ======================= -->
<div class="modal fade" id="modal-bayar" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Pembayaran Biaya: <strong id="bayar-nama-biaya"></strong></h4>
      </div>
      <div class="modal-body">
        <div id="notif-bayar"></div>

        <div class="info-tagihan">
          <div class="row">
            <div class="col-xs-4"><span class="lbl">Nominal Biaya</span><span class="val" id="bayar-bill">0</span></div>
            <div class="col-xs-4"><span class="lbl">Sudah Dibayar</span><span class="val text-green" id="bayar-paid">0</span></div>
            <div class="col-xs-4"><span class="lbl">Sisa</span><span class="val text-red" id="bayar-sisa">0</span></div>
          </div>
        </div>

        <form id="form-bayar" action="<?php echo base_url('invoice_cost/simpan_pembayaran'); ?>" method="post" autocomplete="off">
          <input type="hidden" name="id" value="">
          <input type="hidden" name="invoice_cost_id" value="">
          <div class="row">
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label">Tanggal Bayar</label>
                <input type="text" name="date_of_paid" id="input-tgl-bayar" class="form-control" required
                       data-date-format="dd-mm-yyyy" data-date-end-date="0d" value="<?php echo date('d-m-Y'); ?>">
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label">Nominal Bayar</label>
                <div class="input-group">
                  <span class="input-group-addon">Rp</span>
                  <input type="text" name="paid" id="input-paid" class="form-control text-right" placeholder="0" required>
                </div>
                <span class="help-block" id="help-paid"></span>
              </div>
            </div>
            <div class="col-sm-4">
              <label class="control-label">&nbsp;</label>
              <div>
                <button type="submit" class="btn btn-primary" id="btn-simpan-bayar"><i class="fa fa-save"></i> <span>Simpan Pembayaran</span></button>
                <button type="button" class="btn btn-default" id="btn-batal-edit-bayar" style="display:none"><i class="fa fa-undo"></i> Batal Edit</button>
              </div>
            </div>
          </div>
        </form>

        <div class="table-responsive">
          <table class="table table-bordered table-striped table-condensed" id="tabel-pembayaran">
            <thead>
              <tr>
                <th style="width:40px">No</th>
                <th>Tanggal Bayar</th>
                <th class="text-right">Nominal</th>
                <th class="text-center" style="width:160px">Aksi</th>
              </tr>
            </thead>
            <tbody></tbody>
            <tfoot>
              <tr>
                <th colspan="2">TOTAL PEMBAYARAN</th>
                <th class="text-right" id="foot-bayar">0</th>
                <th></th>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Tutup</button>
      </div>
    </div>
  </div>
</div>


<!-- ======================= MODAL KONFIRMASI HAPUS ======================= -->
<div class="modal modal-danger fade" id="modal-konfirmasi" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="konfirmasi-judul">Konfirmasi Hapus</h4>
      </div>
      <div class="modal-body">
        <p id="konfirmasi-pesan"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-outline" id="btn-konfirmasi-ya"><i class="fa fa-trash"></i> Ya, Hapus</button>
      </div>
    </div>
  </div>
</div>


<script>
/*
 * Memakai jQuery(function($){...}) agar "$" yang dipakai adalah instance jQuery
 * yang sudah memuat plugin (bootstrap modal, datepicker), walaupun footer memuat ulang jquery.min.js.
 */
jQuery(function ($) {

  var URL_DATA        = "<?php echo base_url('invoice_cost/data/' . $id_invoice); ?>";
  var URL_HAPUS_BIAYA = "<?php echo base_url('invoice_cost/hapus_biaya'); ?>";
  var URL_PEMBAYARAN  = "<?php echo base_url('invoice_cost/pembayaran'); ?>";
  var URL_HAPUS_BAYAR = "<?php echo base_url('invoice_cost/hapus_pembayaran'); ?>";
  var TODAY           = "<?php echo date('d-m-Y'); ?>";

  var dataBiaya   = {};      // cache biaya per id
  var biayaAktif  = null;    // biaya yang sedang dibuka di modal pembayaran
  var dataBayar   = {};      // cache pembayaran per id
  var aksiKonfirmasi = null; // callback tombol "Ya, Hapus"

  /* ---------------- Util ---------------- */
  function rp(n) {
    n = parseFloat(n) || 0;
    return n.toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 2 });
  }
  function esc(s) {
    return $('<div>').text(s == null ? '' : s).html();
  }
  function notif(target, tipe, pesan) {
    var icon = tipe === 'success' ? 'fa-check' : (tipe === 'danger' ? 'fa-ban' : 'fa-info');
    $(target).html(
      '<div class="alert alert-' + tipe + ' alert-dismissible">' +
      '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
      '<i class="icon fa ' + icon + '"></i> ' + esc(pesan) + '</div>'
    );
    if (tipe === 'success') {
      setTimeout(function () { $(target).find('.alert').fadeOut(400, function(){ $(this).remove(); }); }, 3000);
    }
  }
  function ajaxGagal(target) {
    return function (xhr) {
      var pesan = 'Terjadi kesalahan koneksi / server.';
      if (xhr.responseJSON && xhr.responseJSON.message) pesan = xhr.responseJSON.message;
      notif(target, 'danger', pesan);
    };
  }

  /* ---------------- AutoNumeric & Datepicker ---------------- */
  var anOpsi = {
    allowDecimalPadding     : false,
    currencySymbol          : '',
    decimalCharacter        : ',',
    digitGroupSeparator     : '.',
    emptyInputBehavior      : 'focus',
    minimumValue            : '0',
    maximumValue            : '9999999999999.99',
    modifyValueOnWheel      : false
  };
  var anBill = new AutoNumeric('#input-bill', anOpsi);
  var anPaid = new AutoNumeric('#input-paid', anOpsi);

  $('#input-tgl-bayar').datepicker({ autoclose: true, format: 'dd-mm-yyyy', endDate: '0d' });

  /* =====================================================================
   *  BIAYA
   * ===================================================================== */
  function muatBiaya() {
    return $.getJSON(URL_DATA).done(function (res) {
      if (!res.status) { notif('#notif-page', 'danger', res.message); return; }

      var tbody = $('#tabel-biaya tbody').empty();
      dataBiaya = {};

      if (res.data.length === 0) {
        tbody.append('<tr><td colspan="7" class="text-center text-muted">Belum ada biaya untuk invoice ini. Klik <strong>Tambah Biaya</strong> untuk menambahkan.</td></tr>');
      }

      $.each(res.data, function (i, c) {
        dataBiaya[c.id] = c;
        var status = c.lunas
          ? '<span class="badge bg-green">Lunas</span>'
          : (c.total_paid > 0 ? '<span class="badge bg-yellow">Sebagian</span>' : '<span class="badge bg-red">Belum Bayar</span>');

        var btnHapus = c.bisa_hapus
          ? '<button type="button" class="btn btn-xs btn-danger btn-hapus-biaya" data-id="' + c.id + '"><i class="fa fa-trash"></i> Hapus</button>'
          : '<button type="button" class="btn btn-xs btn-danger" disabled title="Tidak bisa dihapus: sudah ada ' + c.jumlah_bayar + ' pembayaran"><i class="fa fa-lock"></i> Hapus</button>';

        tbody.append(
          '<tr>' +
            '<td>' + (i + 1) + '</td>' +
            '<td>' + esc(c.bill_name) + '</td>' +
            '<td class="text-right">' + rp(c.bill) + '</td>' +
            '<td class="text-right">' + rp(c.total_paid) + '</td>' +
            '<td class="text-right">' + rp(c.sisa) + '</td>' +
            '<td class="text-center">' + status + '</td>' +
            '<td class="text-center aksi">' +
              '<button type="button" class="btn btn-xs btn-success btn-bayar-biaya" data-id="' + c.id + '"><i class="fa fa-money"></i> Pembayaran (' + c.jumlah_bayar + ')</button> ' +
              '<button type="button" class="btn btn-xs btn-warning btn-edit-biaya" data-id="' + c.id + '"><i class="fa fa-pencil"></i> Edit</button> ' +
              btnHapus +
            '</td>' +
          '</tr>'
        );
      });

      var s = res.summary;
      $('#sum-bill, #foot-bill').text(rp(s.total_bill));
      $('#sum-paid, #foot-paid').text(rp(s.total_paid));
      $('#sum-sisa, #foot-sisa').text(rp(s.sisa));
    }).fail(ajaxGagal('#notif-page'));
  }

  // Tambah
  $('#btn-tambah-biaya').on('click', function () {
    var f = $('#form-biaya');
    f[0].reset();
    f.find('[name="id"]').val('');
    anBill.clear();
    $('#help-bill').text('');
    $('#notif-biaya').empty();
    $('#judul-modal-biaya').text('Tambah Biaya');
    $('#modal-biaya').modal('show');
  });

  // Edit
  $('#tabel-biaya').on('click', '.btn-edit-biaya', function () {
    var c = dataBiaya[$(this).data('id')];
    if (!c) return;
    var f = $('#form-biaya');
    f[0].reset();
    f.find('[name="id"]').val(c.id);
    f.find('[name="bill_name"]').val(c.bill_name);
    anBill.set(c.bill);
    $('#help-bill').text(c.total_paid > 0 ? 'Minimal Rp ' + rp(c.total_paid) + ' (sudah dibayar).' : '');
    $('#notif-biaya').empty();
    $('#judul-modal-biaya').text('Edit Biaya');
    $('#modal-biaya').modal('show');
  });

  $('#modal-biaya').on('shown.bs.modal', function () {
    $(this).find('[name="bill_name"]').trigger('focus');
  });

  // Simpan (tambah / edit)
  $('#form-biaya').on('submit', function (e) {
    e.preventDefault();
    var f = $(this), btn = $('#btn-simpan-biaya');
    btn.prop('disabled', true);
    $.post(f.attr('action'), f.serialize(), null, 'json')
      .done(function (res) {
        if (res.status) {
          $('#modal-biaya').modal('hide');
          notif('#notif-page', 'success', res.message);
          muatBiaya();
        } else {
          notif('#notif-biaya', 'danger', res.message);
        }
      })
      .fail(ajaxGagal('#notif-biaya'))
      .always(function () { btn.prop('disabled', false); });
  });

  // Hapus
  $('#tabel-biaya').on('click', '.btn-hapus-biaya', function () {
    var c = dataBiaya[$(this).data('id')];
    if (!c) return;
    konfirmasi('Hapus Biaya',
      'Yakin ingin menghapus biaya <strong>' + esc(c.bill_name) + '</strong> sebesar Rp ' + rp(c.bill) + '?',
      function (selesai) {
        $.post(URL_HAPUS_BIAYA, { id: c.id }, null, 'json')
          .done(function (res) {
            notif('#notif-page', res.status ? 'success' : 'danger', res.message);
            if (res.status) muatBiaya();
          })
          .fail(ajaxGagal('#notif-page'))
          .always(selesai);
      });
  });

  /* =====================================================================
   *  PEMBAYARAN
   * ===================================================================== */
  function resetFormBayar() {
    var f = $('#form-bayar');
    f.find('[name="id"]').val('');
    f.find('[name="invoice_cost_id"]').val(biayaAktif ? biayaAktif.id : '');
    $('#input-tgl-bayar').datepicker('update', TODAY);
    anPaid.clear();
    $('#btn-simpan-bayar span').text('Simpan Pembayaran');
    $('#btn-batal-edit-bayar').hide();
    $('#tabel-pembayaran tbody tr').removeClass('row-edit');
    if (biayaAktif) {
      $('#help-paid').text(biayaAktif.sisa > 0 ? 'Maksimal Rp ' + rp(biayaAktif.sisa) : 'Biaya sudah lunas.');
      $('#form-bayar :input').not('[type=hidden]').prop('disabled', biayaAktif.sisa <= 0);
    }
  }

  function muatPembayaran(costId) {
    return $.getJSON(URL_PEMBAYARAN + '/' + costId).done(function (res) {
      if (!res.status) { notif('#notif-bayar', 'danger', res.message); return; }

      biayaAktif = res.cost;
      dataBayar = {};
      $('#bayar-nama-biaya').text(res.cost.bill_name);
      $('#bayar-bill').text('Rp ' + rp(res.cost.bill));
      $('#bayar-paid').text('Rp ' + rp(res.cost.total_paid));
      $('#bayar-sisa').text('Rp ' + rp(res.cost.sisa));

      var tbody = $('#tabel-pembayaran tbody').empty();
      if (res.data.length === 0) {
        tbody.append('<tr><td colspan="4" class="text-center text-muted">Belum ada pembayaran.</td></tr>');
      }
      $.each(res.data, function (i, p) {
        dataBayar[p.id] = p;
        tbody.append(
          '<tr data-id="' + p.id + '">' +
            '<td>' + (i + 1) + '</td>' +
            '<td>' + esc(p.tanggal_indo) + '</td>' +
            '<td class="text-right">' + rp(p.paid) + '</td>' +
            '<td class="text-center aksi">' +
              '<button type="button" class="btn btn-xs btn-warning btn-edit-bayar" data-id="' + p.id + '"><i class="fa fa-pencil"></i> Edit</button> ' +
              '<button type="button" class="btn btn-xs btn-danger btn-hapus-bayar" data-id="' + p.id + '"><i class="fa fa-trash"></i> Hapus</button>' +
            '</td>' +
          '</tr>'
        );
      });
      $('#foot-bayar').text(rp(res.cost.total_paid));
      resetFormBayar();
    }).fail(ajaxGagal('#notif-bayar'));
  }

  // Buka modal pembayaran
  $('#tabel-biaya').on('click', '.btn-bayar-biaya', function () {
    var id = $(this).data('id');
    biayaAktif = null;
    $('#notif-bayar').empty();
    $('#tabel-pembayaran tbody').html('<tr><td colspan="4" class="text-center text-muted"><i class="fa fa-spinner fa-spin"></i> Memuat...</td></tr>');
    $('#modal-bayar').modal('show');
    muatPembayaran(id);
  });

  // Refresh daftar biaya saat modal pembayaran ditutup (agar total & status terbaru)
  $('#modal-bayar').on('hidden.bs.modal', function () { muatBiaya(); });

  // Edit pembayaran -> isi form
  $('#tabel-pembayaran').on('click', '.btn-edit-bayar', function () {
    var p = dataBayar[$(this).data('id')];
    if (!p || !biayaAktif) return;
    var f = $('#form-bayar');
    f.find(':input').prop('disabled', false);
    f.find('[name="id"]').val(p.id);
    $('#input-tgl-bayar').datepicker('update', p.tanggal);
    anPaid.set(p.paid);
    $('#help-paid').text('Maksimal Rp ' + rp(biayaAktif.sisa + p.paid));
    $('#btn-simpan-bayar span').text('Update Pembayaran');
    $('#btn-batal-edit-bayar').show();
    $('#tabel-pembayaran tbody tr').removeClass('row-edit');
    $(this).closest('tr').addClass('row-edit');
    $('#input-paid').trigger('focus');
  });

  $('#btn-batal-edit-bayar').on('click', resetFormBayar);

  // Simpan pembayaran (tambah / edit)
  $('#form-bayar').on('submit', function (e) {
    e.preventDefault();
    var f = $(this), btn = $('#btn-simpan-bayar');
    btn.prop('disabled', true);
    $.post(f.attr('action'), f.serialize(), null, 'json')
      .done(function (res) {
        notif('#notif-bayar', res.status ? 'success' : 'danger', res.message);
        if (res.status) muatPembayaran(biayaAktif.id);
      })
      .fail(ajaxGagal('#notif-bayar'))
      .always(function () { btn.prop('disabled', false); });
  });

  // Hapus pembayaran
  $('#tabel-pembayaran').on('click', '.btn-hapus-bayar', function () {
    var p = dataBayar[$(this).data('id')];
    if (!p) return;
    konfirmasi('Hapus Pembayaran',
      'Yakin ingin menghapus pembayaran tanggal <strong>' + esc(p.tanggal_indo) + '</strong> sebesar Rp ' + rp(p.paid) + '?',
      function (selesai) {
        $.post(URL_HAPUS_BAYAR, { id: p.id }, null, 'json')
          .done(function (res) {
            notif('#notif-bayar', res.status ? 'success' : 'danger', res.message);
            if (res.status) muatPembayaran(biayaAktif.id);
          })
          .fail(ajaxGagal('#notif-bayar'))
          .always(selesai);
      });
  });

  /* =====================================================================
   *  KONFIRMASI (dipakai bersama)
   *  Modal konfirmasi bisa tampil di atas modal pembayaran.
   * ===================================================================== */
  function konfirmasi(judul, pesanHtml, aksi) {
    $('#konfirmasi-judul').text(judul);
    $('#konfirmasi-pesan').html(pesanHtml);
    aksiKonfirmasi = aksi;
    $('#modal-konfirmasi').modal('show');
  }

  $('#btn-konfirmasi-ya').on('click', function () {
    if (!aksiKonfirmasi) return;
    var btn = $(this).prop('disabled', true);
    aksiKonfirmasi(function () {
      btn.prop('disabled', false);
      $('#modal-konfirmasi').modal('hide');
    });
    aksiKonfirmasi = null;
  });

  // Stacking modal: konfirmasi di atas modal pembayaran
  $('#modal-konfirmasi').on('show.bs.modal', function () {
    var z = 1050 + 10 * $('.modal:visible').length;
    $(this).css('z-index', z);
    setTimeout(function () { $('.modal-backdrop').not('.stacked').last().css('z-index', z - 1).addClass('stacked'); }, 0);
  });
  $('#modal-konfirmasi').on('hidden.bs.modal', function () {
    if ($('.modal:visible').length) $('body').addClass('modal-open');   // supaya modal pembayaran tetap bisa di-scroll
  });

  /* ---------------- Init ---------------- */
  muatBiaya();
});
</script>
