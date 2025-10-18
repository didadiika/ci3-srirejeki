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
        Buat Slip Gaji
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-money"></i> Payroll</a></li>
        <li class="active">Bayar Gaji</li>
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
          <form role="form" action="<?php echo base_url('payroll/bayar_buat'); ?>" method="post" autocomplete="off" enctype="multipart/form-data">
            <!-- text input -->
            
            <div id="tagNama" class="form-group">
                  <label class="control-label" for="inputError"><i id="iconNama"></i> Tanggal Buat Slip</label>
                  <input type="text" name="tanggal" class="form-control" maxlength="20" required="true" placeholder="Tanggal" value="<?php echo date("d-m-Y");?>" readonly>
                  <span class="help-block" id="pesanNama"></span>
            </div>

            <div id="tagNama" class="form-group">
                  <label class="control-label" for="inputError"><i id="iconNama"></i> Tahun Gaji</label>
                  <select name="tahun" class="form-control" id="tahun" required onChange="cekSlipGanda()">
                    <?php
                    
                        for($t = date("Y")-1; $t <= date("Y"); $t++)
                        {
                            if($t == date("Y"))
                            {
                                echo"<option value='$t' selected='".date("Y")."' >$t</option>";
                            }
                            else
                            {
                                echo"<option value='$t'>$t</option>";
                            }
                            
                        }
                    ?>
                  </select>
                  <span class="help-block" id="pesanNama"></span>
            </div>

            <div id="tagNama" class="form-group">
                  <label class="control-label" for="inputError"><i id="iconNama"></i> Bulan Gaji</label>
                  <select name="bulan"  class="form-control" id="bulan" required onChange="cekSlipGanda()">
                    <?php
                    $nama_bulan = array(1=>"Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
                        for($b = 1; $b <= 12; $b++)
                        {
                            if($b == date("m"))
                            {
                                echo"<option value='$b' selected='".date("m")."' >$nama_bulan[$b]</option>";
                            }
                            else
                            {
                                echo"<option value='$b'>$nama_bulan[$b]</option>";
                            }
                            
                        }
                    ?>
                  </select>
                  <span class="help-block" id="pesanNama"></span>
            </div>

            <div id="tagNama" class="form-group">
                  <label class="control-label" for="inputError"><i id="iconNama"></i> Minggu Ke</label>
                  <input type="number" name="minggu_ke" id="minggu_ke" class="form-control" min="1" max="6" required="true" placeholder="Minggu ke" value="<?php echo getWeeks(date("Y-m-d"), "sunday");?>" onChange="cekSlipGanda()">
                  <span class="help-block" id="pesanNama"></span>
            </div>

          <button class="btn btn-app" type="submit" id="submit">
          <i class="fa fa-plus"></i> Buat Baru
          </button>
         
          <button class="btn btn-app" type="button" onClick="self.history.back()">
          <i class="fa fa-arrow-left"></i> Kembali
          </button>

            </div>
            <div class="col-md-6">

            <div id="tagNama" class="form-group">
                  <label class="control-label" for="inputError"><i id="iconNama"></i> Pegawai</label>
                  <select name="id_pegawai"  class="form-control" id="id_pegawai" required onChange="cekSlipGanda(),showDetailPegawai()">
                  <option value='' selected >Pilih*</option>
                    <?php
                        foreach($pegawai->result() as $s)
                        {
                            echo"<option value='$s->id_pegawai'>$s->nama_pegawai</option>";
                        }
                    ?>
                  </select>
                  <span class="help-block" id="pesanNama"></span>
            </div>

            <div id="tagNama" class="form-group">
                  <label class="control-label" for="inputError"><i id="iconNama"></i> Jabatan : Gaji</label>
                  <input type="text" id="jabatan" class="form-control" placeholder="Jabatan" readonly>
                  <span class="help-block" id="pesanNama"></span>
            </div>

            <div id="tagNama" class="form-group">
                  <label class="control-label" for="inputError"><i id="iconNama"></i> Alamat</label>
                  <input type="text" id="alamat" class="form-control" placeholder="Alamat" readonly>
                  <span class="help-block" id="pesanNama"></span>
            </div>

            <div class="form-group">
                    <label class="control-label" for="inputError"><i id="iconNama"></i>Keterangan</label>
                    <textarea class="form-control" name="keterangan" id="keterangan" placeholder="Keterangan" rows="3"></textarea>
                 
                </div>
          
          
            
          </form>
        
        </div>
        </div>
        </div>
        <!-- /.box-body -->
        
        
      </div>
      <!-- /.row -->
    </section>