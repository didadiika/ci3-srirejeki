
<section class="content-header">
      <h1>
        Laporan Piutang
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-file-text-o"></i> Laporan</a></li>
        <li class="active">Laporan Piutang</li>
      </ol>
    </section>

    <section class="content">
      <div class="box box-warning">
   
        <div class="box-header with-border">
          <h3 class="box-title">Masukkan Data</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <form role="form" action='<?php echo base_url('laporan/laporan-piutang-tampil');?>' method='post' target='_blank' autocomplete="off">
            <div id="tagNama" class="form-group">
                  <label class="control-label" for="inputError"><i id="iconNama"></i> Pelanggan</label>
                  <select name="id_pelanggan"  class="form-control"  required>
                      <option value="">Pilih*</option>
                      <?php 
                      if($pelanggan->num_rows() > 0){
                          foreach($pelanggan->result() as $r){
                              echo"<option value='$r->id_pelanggan'>$r->nama_pelanggan</option>";
                          }
                      }
                      ?>
                  </select>
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