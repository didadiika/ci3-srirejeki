
<section class="content-header">
      <h1>
        Laporan Buku Keuangan
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-file-text-o"></i> Laporan</a></li>
        <li class="active">Laporan Buku Keuangan</li>
      </ol>
    </section>

    <section class="content">
      <div class="box box-warning">
   
        <div class="box-header with-border">
          <h3 class="box-title">Masukkan Data</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <form role="form" action='<?php echo base_url('laporan/laporan-buku-keuangan-tampil');?>' method='post' target='_blank' autocomplete="off">
            

            <div id="tagNama" class="form-group">
                  <label class="control-label" for="inputError"><i id="iconNama"></i> Dari Tanggal</label>
                  <input type="text" name="dari" id="tgl" data-date-format="dd-mm-yyyy" data-date-end-date="0d" class="form-control" maxlength="20" required="true" placeholder="Tanggal" value="<?php echo date("d-m-Y");?>" required>
                  <span class="help-block" id="pesanNama"></span>
            </div>

            <div id="tagNama" class="form-group">
                  <label class="control-label" for="inputError"><i id="iconNama"></i> Sampai Tanggal</label>
                  <input type="text" name="sampai" id="datepicker" data-date-format="dd-mm-yyyy" data-date-end-date="0d" class="form-control" maxlength="20" required="true" placeholder="Tanggal" value="<?php echo date("d-m-Y");?>" required>
                  <span class="help-block" id="pesanNama"></span>
            </div>


           
          
          <button class="btn btn-app" type="submit" id="submit">
          <i class="fa fa-file-text-o"></i> Tampilkan
          </button>
         
          <button class="btn btn-app" type="reset">
          <i class="fa fa-refresh"></i> Ulangi
          </button>
            
          </form>
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.row -->
    </section>