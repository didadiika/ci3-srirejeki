<script>
  function showDetailPegawai(){
    var id = $("#id_pegawai").val();
    
    $.ajax({
      url:"<?php echo base_url('payroll/pegawai_detail_tampil');?>",
      type:"POST",
      data:{"id":id},
      success:function(data){
          var p = JSON.parse(data);
          $("#jabatan").val(p.jabatan);
          $("#alamat").val(p.alamat);
      },
      error:function(){
        console.log("ERROR : showDetailPegawai()! ");
      }
    });
   
  }

  function cekSlipGanda(){
    var id = $("#id_pegawai").val();
    var tahun = $("#tahun").val();
    var bulan = $("#bulan").val();
    var minggu = $("#minggu_ke").val();
    
    $.ajax({
      url:"<?php echo base_url('payroll/pegawai_cek_slip_ganda');?>",
      type:"POST",
      data:{"id":id, "tahun":tahun, "bulan":bulan, "minggu":minggu},
      success:function(data){
          if(data > 0)
          {
            $('#AlertWarning').attr('class','callout callout-danger show');
            $("#submit").prop("disabled",true);
            console.log("cekSlipGanda() berhasil !!!");
          }
          else
          {
            $('#AlertWarning').attr('class','callout callout-danger hide');
            $("#submit").prop("disabled",false);
            console.log("cekSlipGanda() berhasil !!!");
          }
      },
      error:function(){
        console.log("ERROR : showDetailPegawai()! ");
      }
    });
  }
</script>

<section class="content-header">
      <h1>
        Buat Nota Pembelian
      </h1>
      <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-refresh"></i> Transaksi</a></li>
        <li class="active">Pembelian</li>
      </ol>
    </section>

    <section class="content">
    
      <div class="box box-warning">
        <div class="box-header with-border">
          <h3 class="box-title">Masukkan Data</h3>
        </div>
        <!-- /.box-header -->
    
        <div class="box-body">
        <div class="callout callout-danger hide" id="AlertWarning">
        <h4>Error</h4>
        Slip Gaji pegawai ini sudah ada !!!
        </div>

        <div class="row">
        <div class="col-md-6">
          <form role="form" action="<?php echo base_url('pembelian/buat'); ?>" method="post" autocomplete="off" enctype="multipart/form-data">
            <!-- text input -->
            
            <div id="tagNama" class="form-group">
                  <label class="control-label" for="inputError"><i id="iconNama"></i> Tanggal</label>
                  <input type="text" name="tanggal" id="tgl" data-date-format="dd-mm-yyyy" data-date-end-date="0d" class="form-control" maxlength="20" required="true" placeholder="Tanggal" value="<?php echo date("d-m-Y");?>" required>
                  <span class="help-block" id="pesanNama"></span>
            </div>

            <div id="tagNama" class="form-group">
                  <label class="control-label" for="inputError"><i id="iconNama"></i> Nama Suplier</label>
                  <input type="text" name="nama_suplier" id="nama_suplier" class="form-control" required="true" placeholder="Nama Suplier" maxlength="50">
                  <span class="help-block" id="pesanNama"></span>
            </div>

            <div id="tagNama" class="form-group">
                  <label class="control-label" for="inputError"><i id="iconNama"></i> No Nota</label>
                  <input type="text" name="no_nota" id="no_nota" class="form-control" required="true" placeholder="No Nota" maxlength="50">
                  <span class="help-block" id="pesanNama"></span>
            </div>

            </div>
            <div class="col-md-6">

            <div class="form-group">
                    <label class="control-label" for="inputError"><i id="iconNama"></i>Keterangan</label>
                    <textarea class="form-control" name="keterangan" id="keterangan" placeholder="Keterangan" rows="3"></textarea>
                 
                </div>

            <button class="btn btn-app" type="submit" id="submit">
          <i class="fa fa-plus"></i> Buat Baru
          </button>
         
          <button class="btn btn-app" type="button" onClick="self.history.back()">
          <i class="fa fa-arrow-left"></i> Kembali
          </button>
          
            
          </form>
        
        </div>
        </div>
        </div>
        <!-- /.box-body -->
        
        
      </div>
      <!-- /.row -->
    </section>