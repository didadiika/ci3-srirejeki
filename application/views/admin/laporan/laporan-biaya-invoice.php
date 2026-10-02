<section class="content-header">
  <h1>Laporan Biaya Invoice</h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-file-text-o"></i> Laporan</a></li>
    <li class="active">Laporan Biaya Invoice</li>
  </ol>
</section>

<section class="content">
  <div class="box box-warning">
    <div class="box-header with-border">
      <h3 class="box-title">Masukkan Data</h3>
    </div>
    <div class="box-body">
      <form role="form" action="<?php echo base_url('laporan/laporan-biaya-invoice-tampil'); ?>" method="post" target="_blank" autocomplete="off">

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label class="control-label">Dari Tanggal Invoice</label>
              <input type="text" name="dari" id="tgl" data-date-format="dd-mm-yyyy" class="form-control" required
                     value="<?php echo date('01-m-Y'); ?>">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label class="control-label">Sampai Tanggal Invoice</label>
              <input type="text" name="sampai" id="datepicker" data-date-format="dd-mm-yyyy" class="form-control" required
                     value="<?php echo date('d-m-Y'); ?>">
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label class="control-label">Kategori</label>
              <select name="id_kategori" class="form-control">
                <option value="*">Semua Kategori</option>
                <?php foreach ($kategori as $k) { ?>
                  <option value="<?php echo $k->id; ?>"><?php echo html_escape($k->nama_kategori); ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label class="control-label">Pelanggan</label>
              <select name="id_pelanggan" class="form-control select2" style="width:100%">
                <option value="*">Semua Pelanggan</option>
                <?php foreach ($pelanggan->result() as $p) { ?>
                  <option value="<?php echo $p->id_pelanggan; ?>"><?php echo html_escape($p->nama_pelanggan); ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label class="control-label">Status Pembayaran Biaya</label>
              <select name="status" class="form-control">
                <option value="Semua">Semua</option>
                <option value="Belum Lunas">Belum Lunas saja</option>
                <option value="Lunas">Lunas saja</option>
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label class="control-label">Jenis Laporan</label>
              <select name="jenis" class="form-control">
                <option value="Ringkas">Ringkas (per Invoice)</option>
                <option value="Rinci">Rinci (per Biaya)</option>
              </select>
            </div>
          </div>
        </div>

        <p class="text-muted" style="margin-top:-5px">
          <i class="fa fa-info-circle"></i> Rentang tanggal mengacu pada <strong>tanggal invoice</strong>.
          Status bayar dihitung dari seluruh pembayaran biaya hingga hari ini.
        </p>

        <button class="btn btn-app" type="submit"><i class="fa fa-file-text-o"></i> Tampilkan</button>
        <button class="btn btn-app" type="reset"><i class="fa fa-refresh"></i> Ulangi</button>
      </form>
    </div>
  </div>
</section>
