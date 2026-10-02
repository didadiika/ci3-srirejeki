<?php
/**
 * Detail Bulog: daftar truk + timbangan per truk
 * Variabel: $bulog
 */
?>
<style>
  .bulog-ringkas .info-box { min-height: 70px; margin-bottom: 10px; }
  .bulog-ringkas .info-box-icon { height: 70px; line-height: 70px; width: 70px; font-size: 30px; }
  .bulog-ringkas .info-box-content { margin-left: 70px; }

  .truk-box .box-title { font-size: 17px; }
  .truk-box .box-title small { color: #777; font-size: 13px; margin-left: 6px; }
  .truk-box .berat-truk { font-size: 17px; font-weight: bold; margin-right: 8px; }
  .form-timbang { display: flex; gap: 8px; max-width: 420px; margin-bottom: 12px; }
  .form-timbang input { font-size: 16px; font-weight: bold; text-align: right; }

  .grid-timbang { display: grid; grid-template-columns: repeat(10, 1fr); border-top: 1px solid #ccc; border-left: 1px solid #ccc; }
  .grid-timbang .sel { border-right: 1px solid #ccc; border-bottom: 1px solid #ccc; text-align: center; padding: 4px 2px 3px; cursor: pointer; position: relative; background: #fff; }
  .grid-timbang .sel:hover { background: #fcf8e3; }
  .grid-timbang .sel .nomor { position: absolute; top: 1px; left: 3px; font-size: 9px; color: #aaa; }
  .grid-timbang .sel .angka { font-size: 15px; font-weight: bold; }
  .grid-timbang .sel.baru { animation: kilat 1.2s ease-out; }
  @keyframes kilat { from { background: #d4edda; } to { background: #fff; } }
  .kosong-timbang { color: #999; font-style: italic; padding: 8px 0; }
  @media (max-width: 767px) { .grid-timbang { grid-template-columns: repeat(5, 1fr); } }

  #tabel-rekap td, #tabel-rekap th { vertical-align: middle; }
</style>

<section class="content-header">
  <h1>Timbangan Bulog <small id="judul-tanggal"><?php echo tgl_indo($bulog->date); ?></small></h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-refresh"></i> Transaksi</a></li>
    <li><a href="<?php echo base_url('transaksi/bulog'); ?>">Bulog</a></li>
    <li class="active">Timbangan</li>
  </ol>
</section>

<section class="content">

  <div class="box box-warning">
    <div class="box-body">
      <div class="row bulog-ringkas">
        <div class="col-sm-4">
          <div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="fa fa-balance-scale"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Total Berat Bulog</span>
              <span class="info-box-number" id="sum-berat">0 kg</span>
            </div>
          </div>
        </div>
        <div class="col-sm-4">
          <div class="info-box">
            <span class="info-box-icon bg-green"><i class="fa fa-truck"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Jumlah Truk</span>
              <span class="info-box-number" id="sum-truk">0</span>
            </div>
          </div>
        </div>
        <div class="col-sm-4">
          <div class="info-box">
            <span class="info-box-icon bg-yellow"><i class="fa fa-list-ol"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Jumlah Timbang</span>
              <span class="info-box-number" id="sum-timbang">0 kali</span>
            </div>
          </div>
        </div>
      </div>

      <a href="javascript:;" class="btn btn-app" id="btn-tambah-truk"><i class="fa fa-plus"></i> Tambah Truk</a>
      <a href="<?php echo base_url('transaksi/bulog/cetak/' . $bulog->id); ?>" target="_blank" class="btn btn-app"><i class="fa fa-print"></i> Cetak</a>
      <a href="<?php echo base_url('transaksi/bulog'); ?>" class="btn btn-app"><i class="fa fa-arrow-left"></i> Kembali</a>

      <div class="callout callout-info" style="padding:8px 15px; margin:10px 0 0">
        <i class="fa fa-info-circle"></i> Ketik berat lalu tekan <strong>Enter</strong> untuk menambah timbangan.
        Klik angka timbangan untuk <strong>mengubah / menghapus</strong>. Truk hanya bisa dihapus jika belum ada timbangan.
      </div>
    </div>
  </div>

  <div id="notif-page"></div>

  <div id="daftar-truk">
    <div class="text-center text-muted" style="padding:30px"><i class="fa fa-spinner fa-spin"></i> Memuat data...</div>
  </div>

  <div class="box box-primary" id="box-rekap" style="display:none">
    <div class="box-header with-border"><h3 class="box-title">Rekap per Truk</h3></div>
    <div class="box-body table-responsive">
      <table class="table table-bordered table-striped" id="tabel-rekap">
        <thead>
          <tr>
            <th style="width:40px">No</th>
            <th>No Truk</th>
            <th>Nama</th>
            <th>Kota</th>
            <th class="text-center">Jml Timbang</th>
            <th class="text-right">Berat (kg)</th>
          </tr>
        </thead>
        <tbody></tbody>
        <tfoot>
          <tr>
            <th colspan="4">TOTAL</th>
            <th class="text-center" id="rekap-timbang">0</th>
            <th class="text-right" id="rekap-berat">0</th>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>
</section>

<!-- ======================= MODAL TRUK ======================= -->
<div class="modal fade" id="modal-truk" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="judul-modal-truk">Tambah Truk</h4>
      </div>
      <form id="form-truk" action="<?php echo base_url('bulog/simpan_truk'); ?>" method="post" autocomplete="off">
        <div class="modal-body">
          <div id="notif-truk"></div>
          <input type="hidden" name="id" value="">
          <input type="hidden" name="bulog_id" value="<?php echo $bulog->id; ?>">
          <div class="form-group">
            <label class="control-label">No Truk</label>
            <input type="text" name="truck_number" class="form-control" placeholder="Contoh: K 8089 GT" maxlength="30" required style="text-transform:uppercase">
          </div>
          <div class="form-group">
            <label class="control-label">Nama <small class="text-muted">(opsional)</small></label>
            <input type="text" name="name" class="form-control" placeholder="Nama" maxlength="100" list="daftar-nama">
            <datalist id="daftar-nama"></datalist>
          </div>
          <div class="form-group">
            <label class="control-label">Kota</label>
            <input type="text" name="city" class="form-control" placeholder="Contoh: Kudus" maxlength="100" required list="daftar-kota">
            <datalist id="daftar-kota"></datalist>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-times"></i> Batal</button>
          <button type="submit" class="btn btn-primary" id="btn-simpan-truk"><i class="fa fa-save"></i> Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- ======================= MODAL EDIT TIMBANGAN ======================= -->
<div class="modal modal-warning fade" id="modal-timbang" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Ubah Timbangan <span id="info-timbang"></span></h4>
      </div>
      <form id="form-edit-timbang" action="<?php echo base_url('bulog/simpan_timbangan'); ?>" method="post" autocomplete="off">
        <div class="modal-body">
          <div id="notif-timbang"></div>
          <input type="hidden" name="id" value="">
          <input type="hidden" name="bulog_order_id" value="">
          <div class="form-group">
            <label class="control-label">Berat (kg)</label>
            <input type="text" name="weight" inputmode="numeric" class="form-control input-lg text-right" required>
          </div>
          <p class="small" id="jam-timbang" style="margin:0"></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline pull-left" id="btn-hapus-timbang"><i class="fa fa-trash"></i> <span>Hapus</span></button>
          <button type="submit" class="btn btn-outline" id="btn-update-timbang"><i class="fa fa-save"></i> Update</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- ======================= MODAL HAPUS TRUK ======================= -->
<div class="modal modal-danger fade" id="modal-hapus-truk" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Konfirmasi Hapus Truk</h4>
      </div>
      <div class="modal-body"><p id="pesan-hapus-truk"></p></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-outline" id="btn-hapus-truk-ya"><i class="fa fa-trash"></i> Ya, Hapus</button>
      </div>
    </div>
  </div>
</div>

<script>
jQuery(function ($) {
  var URL_DATA  = '<?php echo base_url('bulog/data/' . $bulog->id); ?>';
  var URL_TIMB  = '<?php echo base_url('bulog/simpan_timbangan'); ?>';
  var URL_HTIMB = '<?php echo base_url('bulog/hapus_timbangan'); ?>';
  var URL_HTRUK = '<?php echo base_url('bulog/hapus_truk'); ?>';

  var dataTruk = {}, dataTimbang = {}, idHapusTruk = null;

  /* ---------------- util ---------------- */
  function kg(n) { return (parseInt(n, 10) || 0).toLocaleString('id-ID'); }
  function esc(s) { return $('<div>').text(s == null ? '' : s).html(); }
  function notif(target, tipe, pesan) {
    $(target).html('<div class="alert alert-' + tipe + ' alert-dismissible">' +
      '<button type="button" class="close" data-dismiss="alert">&times;</button>' + esc(pesan) + '</div>');
    if (tipe === 'success') setTimeout(function () { $(target).find('.alert').fadeOut(400, function(){ $(this).remove(); }); }, 2500);
  }
  function gagal(target) {
    return function (xhr) { notif(target, 'danger', (xhr.responseJSON && xhr.responseJSON.message) || 'Terjadi kesalahan koneksi / server.'); };
  }
  // format ribuan saat mengetik (2000 -> 2.000)
  $(document).on('input', '.input-berat, #form-edit-timbang [name=weight]', function () {
    var v = this.value.replace(/\D/g, '').replace(/^0+/, '');
    this.value = v ? parseInt(v, 10).toLocaleString('id-ID') : '';
  });

  /* ---------------- render ---------------- */
  function render(res, fokusTruk, idBaru) {
    var b = res.bulog;
    $('#judul-tanggal').text(b.date_indo);
    $('#sum-berat').text(kg(b.total_weight) + ' kg');
    $('#sum-truk').text(b.jumlah_truk);
    $('#sum-timbang').text(b.jumlah_timbang + ' kali');

    dataTruk = {}; dataTimbang = {};
    var wadah = $('#daftar-truk').empty(), rekap = $('#tabel-rekap tbody').empty(), kota = {}, nama = {};

    if (res.orders.length === 0) {
      wadah.html('<div class="box box-default"><div class="box-body text-center text-muted" style="padding:30px">' +
        '<i class="fa fa-truck fa-2x"></i><br>Belum ada truk. Klik <strong>Tambah Truk</strong> untuk mulai menimbang.</div></div>');
      $('#box-rekap').hide();
      return;
    }

    $.each(res.orders, function (i, o) {
      dataTruk[o.id] = o;
      kota[o.city] = true;
      if (o.name) nama[o.name] = true;

      var grid = '';
      $.each(o.weights, function (j, w) {
        dataTimbang[w.id] = $.extend({ order_id: o.id, urut: j + 1, truck: o.truck_number }, w);
        grid += '<div class="sel' + (w.id === idBaru ? ' baru' : '') + '" data-id="' + w.id + '" title="' + esc(w.jam) + '">' +
                  '<span class="nomor">' + (j + 1) + '</span><span class="angka">' + kg(w.weight) + '</span></div>';
      });

      var btnHapus = o.bisa_hapus
        ? '<button type="button" class="btn btn-box-tool btn-hapus-truk" data-id="' + o.id + '" title="Hapus truk"><i class="fa fa-trash text-red"></i></button>'
        : '<button type="button" class="btn btn-box-tool" disabled title="Tidak bisa dihapus: sudah ada timbangan"><i class="fa fa-lock"></i></button>';

      wadah.append(
        '<div class="box box-solid box-default truk-box" id="truk-' + o.id + '">' +
          '<div class="box-header with-border">' +
            '<h3 class="box-title"><i class="fa fa-truck"></i> ' + esc(o.truck_number) +
              (o.name ? '<small><i class="fa fa-user"></i> ' + esc(o.name) + '</small>' : '') +
              '<small><i class="fa fa-map-marker"></i> ' + esc(o.city) + '</small></h3>' +
            '<div class="box-tools pull-right">' +
              '<span class="berat-truk">' + kg(o.weight_by_truck) + ' kg</span>' +
              '<span class="badge bg-gray" style="margin-right:6px">' + o.jumlah_timbang + 'x timbang</span>' +
              '<button type="button" class="btn btn-box-tool btn-edit-truk" data-id="' + o.id + '" title="Edit truk"><i class="fa fa-pencil text-yellow"></i></button>' +
              btnHapus +
            '</div>' +
          '</div>' +
          '<div class="box-body">' +
            '<form class="form-timbang" data-order="' + o.id + '" autocomplete="off">' +
              '<div class="input-group" style="flex:1">' +
                '<input type="text" inputmode="numeric" class="form-control input-berat" placeholder="Berat timbangan" required>' +
                '<span class="input-group-addon">kg</span>' +
              '</div>' +
              '<button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i> Tambah</button>' +
            '</form>' +
            (grid ? '<div class="grid-timbang">' + grid + '</div>' : '<div class="kosong-timbang">Belum ada timbangan untuk truk ini.</div>') +
          '</div>' +
        '</div>'
      );

      rekap.append('<tr><td>' + (i + 1) + '</td><td>' + esc(o.truck_number) + '</td><td>' + (o.name ? esc(o.name) : '<span class="text-muted">-</span>') + '</td><td>' + esc(o.city) + '</td>' +
        '<td class="text-center">' + o.jumlah_timbang + '</td><td class="text-right">' + kg(o.weight_by_truck) + '</td></tr>');
    });

    $('#rekap-timbang').text(b.jumlah_timbang);
    $('#rekap-berat').text(kg(b.total_weight));
    $('#box-rekap').show();

    $('#daftar-nama').html($.map(Object.keys(nama), function (k) { return '<option value="' + esc(k) + '">'; }).join(''));
    $('#daftar-kota').html($.map(Object.keys(kota), function (k) { return '<option value="' + esc(k) + '">'; }).join(''));

    if (fokusTruk) $('#truk-' + fokusTruk + ' .input-berat').trigger('focus');
  }

  function muat(fokusTruk, idBaru) {
    return $.getJSON(URL_DATA).done(function (res) {
      if (!res.status) { notif('#notif-page', 'danger', res.message); return; }
      render(res, fokusTruk, idBaru);
    }).fail(gagal('#notif-page'));
  }

  /* ---------------- truk ---------------- */
  $('#btn-tambah-truk').on('click', function () {
    var f = $('#form-truk'); f[0].reset(); f.find('[name=id]').val('');
    $('#judul-modal-truk').text('Tambah Truk'); $('#notif-truk').empty();
    $('#modal-truk').modal('show');
  });

  $('#daftar-truk').on('click', '.btn-edit-truk', function () {
    var o = dataTruk[$(this).data('id')]; if (!o) return;
    var f = $('#form-truk'); f[0].reset();
    f.find('[name=id]').val(o.id); f.find('[name=truck_number]').val(o.truck_number); f.find('[name=name]').val(o.name || ''); f.find('[name=city]').val(o.city);
    $('#judul-modal-truk').text('Edit Truk'); $('#notif-truk').empty();
    $('#modal-truk').modal('show');
  });

  $('#modal-truk').on('shown.bs.modal', function () { $(this).find('[name=truck_number]').trigger('focus'); });

  $('#form-truk').on('submit', function (e) {
    e.preventDefault();
    var f = $(this), btn = $('#btn-simpan-truk').prop('disabled', true);
    $.post(f.attr('action'), f.serialize(), null, 'json')
      .done(function (res) {
        if (!res.status) { notif('#notif-truk', 'danger', res.message); return; }
        $('#modal-truk').modal('hide');
        notif('#notif-page', 'success', res.message);
        muat(res.id || null);   // truk baru -> langsung fokus ke input beratnya
      })
      .fail(gagal('#notif-truk'))
      .always(function () { btn.prop('disabled', false); });
  });

  $('#daftar-truk').on('click', '.btn-hapus-truk', function () {
    var o = dataTruk[$(this).data('id')]; if (!o) return;
    idHapusTruk = o.id;
    $('#pesan-hapus-truk').html('Yakin ingin menghapus truk <strong>' + esc(o.truck_number) + '</strong>' + (o.name ? ' - ' + esc(o.name) : '') + ' (' + esc(o.city) + ')?');
    $('#modal-hapus-truk').modal('show');
  });

  $('#btn-hapus-truk-ya').on('click', function () {
    if (!idHapusTruk) return;
    var btn = $(this).prop('disabled', true);
    $.post(URL_HTRUK, { id: idHapusTruk }, null, 'json')
      .done(function (res) { notif('#notif-page', res.status ? 'success' : 'danger', res.message); if (res.status) muat(); })
      .fail(gagal('#notif-page'))
      .always(function () { btn.prop('disabled', false); $('#modal-hapus-truk').modal('hide'); idHapusTruk = null; });
  });

  /* ---------------- timbangan: tambah ---------------- */
  $('#daftar-truk').on('submit', '.form-timbang', function (e) {
    e.preventDefault();
    var f = $(this), orderId = f.data('order'), input = f.find('.input-berat'), btn = f.find('button');
    if (!input.val()) return;
    btn.prop('disabled', true); input.prop('readonly', true);
    $.post(URL_TIMB, { bulog_order_id: orderId, weight: input.val() }, null, 'json')
      .done(function (res) {
        if (!res.status) { notif('#notif-page', 'danger', res.message); input.prop('readonly', false).trigger('select'); btn.prop('disabled', false); return; }
        // tandai sel terakhir sebagai baru setelah render
        muat(orderId).done(function () {
          var sel = $('#truk-' + orderId + ' .grid-timbang .sel').last().addClass('baru');
        });
      })
      .fail(function (xhr) { gagal('#notif-page')(xhr); input.prop('readonly', false); btn.prop('disabled', false); });
  });

  /* ---------------- timbangan: edit / hapus ---------------- */
  $('#daftar-truk').on('click', '.grid-timbang .sel', function () {
    var w = dataTimbang[$(this).data('id')]; if (!w) return;
    var f = $('#form-edit-timbang');
    f.find('[name=id]').val(w.id);
    f.find('[name=bulog_order_id]').val(w.order_id);
    f.find('[name=weight]').val(kg(w.weight));
    $('#info-timbang').text('#' + w.urut + ' · ' + w.truck);
    $('#jam-timbang').text('Ditimbang: ' + w.jam);
    $('#btn-hapus-timbang').removeData('yakin').find('span').text('Hapus');
    $('#notif-timbang').empty();
    $('#modal-timbang').modal('show');
  });

  $('#modal-timbang').on('shown.bs.modal', function () { $(this).find('[name=weight]').trigger('select'); });

  $('#form-edit-timbang').on('submit', function (e) {
    e.preventDefault();
    var f = $(this), btn = $('#btn-update-timbang').prop('disabled', true), orderId = f.find('[name=bulog_order_id]').val();
    $.post(f.attr('action'), f.serialize(), null, 'json')
      .done(function (res) {
        if (!res.status) { notif('#notif-timbang', 'danger', res.message); return; }
        $('#modal-timbang').modal('hide');
        notif('#notif-page', 'success', res.message);
        muat(orderId);
      })
      .fail(gagal('#notif-timbang'))
      .always(function () { btn.prop('disabled', false); });
  });

  // Hapus timbangan: klik pertama minta konfirmasi, klik kedua eksekusi
  $('#btn-hapus-timbang').on('click', function () {
    var btn = $(this);
    if (!btn.data('yakin')) { btn.data('yakin', true).find('span').text('Klik lagi untuk hapus'); return; }
    var f = $('#form-edit-timbang'), orderId = f.find('[name=bulog_order_id]').val();
    btn.prop('disabled', true);
    $.post(URL_HTIMB, { id: f.find('[name=id]').val() }, null, 'json')
      .done(function (res) {
        if (!res.status) { notif('#notif-timbang', 'danger', res.message); return; }
        $('#modal-timbang').modal('hide');
        notif('#notif-page', 'success', res.message);
        muat(orderId);
      })
      .fail(gagal('#notif-timbang'))
      .always(function () { btn.prop('disabled', false); });
  });

  muat();
});
</script>
