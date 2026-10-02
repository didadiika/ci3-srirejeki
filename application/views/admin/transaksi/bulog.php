<section class="content-header">
  <h1>Bulog <small>Barang transit (hanya ditimbang)</small></h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-refresh"></i> Transaksi</a></li>
    <li class="active">Bulog</li>
  </ol>
</section>

<section class="content">

  <a class="btn btn-app" href="javascript:;" id="btn-tambah"><i class="fa fa-plus"></i> Tambah Bulog</a>

  <div id="notif-page"></div>

  <div class="box box-primary">
    <div class="box-header with-border">
      <h3 class="box-title">Daftar Bulog</h3>
    </div>
    <div class="box-body">
      <table id="tabel-bulog" class="table table-bordered table-striped" style="width:100%">
        <thead>
          <tr>
            <th style="width:40px">No</th>
            <th>Tanggal</th>
            <th>Jumlah Truk</th>
            <th class="text-right">Total Berat</th>
            <th class="text-center" style="width:280px">Aksi</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
</section>

<!-- ======================= MODAL TAMBAH / EDIT ======================= -->
<div class="modal fade" id="modal-bulog" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="judul-modal">Tambah Bulog</h4>
      </div>
      <form id="form-bulog" action="<?php echo base_url('bulog/simpan'); ?>" method="post" autocomplete="off">
        <div class="modal-body">
          <div id="notif-modal"></div>
          <input type="hidden" name="id" value="">
          <div class="form-group">
            <label class="control-label">Tanggal</label>
            <input type="text" name="date" id="input-tanggal" class="form-control" required
                   data-date-format="dd-mm-yyyy" value="<?php echo date('d-m-Y'); ?>">
          </div>
          <p class="text-muted small" id="info-tambah" style="margin:0">
            <i class="fa fa-info-circle"></i> Setelah disimpan Anda langsung diarahkan ke halaman timbang untuk menambah truk.
          </p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-times"></i> Batal</button>
          <button type="submit" class="btn btn-primary" id="btn-simpan"><i class="fa fa-save"></i> Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- ======================= MODAL HAPUS ======================= -->
<div class="modal modal-danger fade" id="modal-hapus" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Konfirmasi Hapus Bulog</h4>
      </div>
      <div class="modal-body"><p id="pesan-hapus"></p></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-outline" id="btn-hapus-ya"><i class="fa fa-trash"></i> Ya, Hapus</button>
      </div>
    </div>
  </div>
</div>

<script>
jQuery(function ($) {
  var idHapus = null;

  function esc(s) { return $('<div>').text(s == null ? '' : s).html(); }
  function notif(target, tipe, pesan) {
    $(target).html('<div class="alert alert-' + tipe + ' alert-dismissible">' +
      '<button type="button" class="close" data-dismiss="alert">&times;</button>' + esc(pesan) + '</div>');
    if (tipe === 'success') setTimeout(function () { $(target).find('.alert').fadeOut(400, function(){ $(this).remove(); }); }, 3000);
  }
  function gagal(target) {
    return function (xhr) {
      notif(target, 'danger', (xhr.responseJSON && xhr.responseJSON.message) || 'Terjadi kesalahan koneksi / server.');
    };
  }

  var tabel = $('#tabel-bulog').DataTable({
    language   : { url: '//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Indonesian.json' },
    processing : true,
    serverSide : true,
    autoWidth  : false,
    scrollX    : true,
    order      : [],
    ajax       : { url: '<?php echo base_url('bulog/tampil'); ?>', type: 'GET' },
    columnDefs : [
      { targets: [0, 4], orderable: false, searchable: false }
    ]
  });

  $('#input-tanggal').datepicker({ autoclose: true, format: 'dd-mm-yyyy' });

  // Tambah
  $('#btn-tambah').on('click', function () {
    $('#form-bulog [name=id]').val('');
    $('#input-tanggal').datepicker('update', '<?php echo date('d-m-Y'); ?>');
    $('#judul-modal').text('Tambah Bulog');
    $('#info-tambah').show();
    $('#notif-modal').empty();
    $('#modal-bulog').modal('show');
  });

  // Edit tanggal
  $('#tabel-bulog').on('click', '.item_edit', function () {
    $('#form-bulog [name=id]').val($(this).data('id'));
    $('#input-tanggal').datepicker('update', $(this).data('tanggal'));
    $('#judul-modal').text('Edit Tanggal Bulog');
    $('#info-tambah').hide();
    $('#notif-modal').empty();
    $('#modal-bulog').modal('show');
  });

  $('#form-bulog').on('submit', function (e) {
    e.preventDefault();
    var f = $(this), btn = $('#btn-simpan').prop('disabled', true), baru = f.find('[name=id]').val() === '';
    $.post(f.attr('action'), f.serialize(), null, 'json')
      .done(function (res) {
        if (!res.status) { notif('#notif-modal', 'danger', res.message); return; }
        if (baru && res.url) { window.location.href = res.url; return; }   // langsung ke halaman timbang
        $('#modal-bulog').modal('hide');
        notif('#notif-page', 'success', res.message);
        tabel.ajax.reload(null, false);
      })
      .fail(gagal('#notif-modal'))
      .always(function () { btn.prop('disabled', false); });
  });

  // Hapus (soft delete)
  $('#tabel-bulog').on('click', '.item_hapus', function () {
    idHapus = $(this).data('id');
    var truk = parseInt($(this).data('truk'), 10) || 0;
    $('#pesan-hapus').html('Yakin ingin menghapus Bulog tanggal <strong>' + esc($(this).data('tanggal')) + '</strong>' +
      (truk > 0 ? ' beserta <strong>' + truk + ' truk</strong> di dalamnya' : '') + '?');
    $('#modal-hapus').modal('show');
  });

  $('#btn-hapus-ya').on('click', function () {
    if (!idHapus) return;
    var btn = $(this).prop('disabled', true);
    $.post('<?php echo base_url('bulog/hapus'); ?>', { id: idHapus }, null, 'json')
      .done(function (res) {
        notif('#notif-page', res.status ? 'success' : 'danger', res.message);
        if (res.status) tabel.ajax.reload(null, false);
      })
      .fail(gagal('#notif-page'))
      .always(function () { btn.prop('disabled', false); $('#modal-hapus').modal('hide'); idHapus = null; });
  });
});
</script>
