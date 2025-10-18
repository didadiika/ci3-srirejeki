
<section class="content-header">
      <h1>
        Laporan Gaji
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-file-text-o"></i> Laporan</a></li>
        <li class="active">Laporan Gaji</li>
      </ol>
    </section>

    <section class="content">
      <div class="box box-warning">
   
        <div class="box-header with-border">
          <h3 class="box-title">Masukkan Data</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <form role="form" action='<?php echo base_url('payroll/laporan-gaji-tampil');?>' method='post' target='_blank' autocomplete="off">
            

          <div id="tagNama" class="form-group">
          <label class="control-label" for="inputError"><i id="iconNama"></i> Pilih Tahun</label>
                  <select name="tahun" id="tahun"  required class="form-control">
    <option value="">*Pilih</option>
    <?php
    
    for($i = date("Y")-3; $i <= date("Y"); $i++)
    {
        if(date("Y") == $i)
        {
            echo"<option value='$i' selected='".date("Y")."' >$i</option>";
        }
        else
        {
            echo"<option value='$i'>$i</option>";
        }
        
    }
    ?>
    </select>
                  <span class="help-block" id="pesanNama"></span>
            </div>

            <div id="tagNama" class="form-group">
                  <label class="control-label" for="inputError"><i id="iconNama"></i> Pilih Bulan</label>
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