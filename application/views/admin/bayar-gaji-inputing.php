<script>
$(document).ready(function(){
$('#hapusButton').on('click',function(){
            var id=$(this).attr('data');
            $('#ModalHapus').modal('show');
            $('[name="id_hapus"]').val(id);
        });
       
});

function showNominal(){
var id_gaji = $("#id_gaji").val();
var id_g = $("#id_gaji_p").val();
var id_pegawai = $("#id_pegawai").val();

$.ajax({
  url:"<?php echo base_url('payroll/tampil_gaji_harian_pegawai');?>",
  type:"POST",
  data:{"id_gaji":id_gaji,"id_g":id_g,"id_pegawai":id_pegawai},
  success:function(response){
      if(id_gaji == id_g)
      {
        $("#gaji").val(response);
      }
      else
      {
        $("#gaji").val(0);
      }
      hitungGaji();
  },
  error:function(){
    console.log("ERROR showNominal()");
  }
});
}

function hitungGaji(){
var jumlah = $("#jumlah").val();
var gaji = $("#gaji").val().toString().replace(/\./g,"");

var total = parseInt(gaji) * parseInt(jumlah);
$("#total").val(uang(total));
}
</script>
<div class="modal modal-warning fade" id="ModalWarning" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">X</span></button>
                        <h4 class="modal-title" id="myModalLabel">Peringatan</h4>
                    </div>
                    <div class="modal-body">
                                           
                            
                            <div class="alert alert-default"><p>Slip Gaji ini sudah ada !!!</div>
                                         
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
</div>
<!--MODAL HAPUS-->
<div class="modal modal-danger fade" id="ModalHapus" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">X</span></button>
                        <h4 class="modal-title" id="myModalLabel">Konfirmasi Penghapusan</h4>
                    </div>
                    <form class="form-horizontal" action="<?php echo base_url('payroll/bayar_hapus');?>" method="post">
                    <div class="modal-body">
                                           
                            <input type="hidden" name="id_hapus" id="id_hapus" value="">
                            <div class="alert alert-default"><p>Apakah Anda yakin mau menghapus Slip Gaji ini ?</p></div>
                                         
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn_hapus btn btn-danger" id="btn_hapus" data="<?php echo $this->uri->segment(3); ?>">Hapus</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        <!--END MODAL HAPUS-->
<section class="content-header">
      <h1>
        Buat Slip Gaji
      </h1>
      <ol class="breadcrumb">
      <li class="active"><i class="fa fa-money"></i> Bayar Gaji</li>
      </ol>
    </section>

    <section class="content">
    
      <div class="box box-warning">
        <div class="box-header with-border">
          <h3 class="box-title">Data Slip</h3>
        </div>
        <!-- /.box-header -->
    
        <div class="box-body">
            <?php
            if($slip->num_rows() > 0){
            foreach($slip->result() as $r){
            ?>
            <form role="form" action="<?php echo base_url('payroll/bayar_simpan'); ?>" method="post" autocomplete="off" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $this->enkripsi_url->encode($r->id_gaji_slip);?>">
            <div class="table-responsive">
            <table class="table">
              <tr>
                <th style="width:40%">Tanggal Buat Slip :</th>
                <td><?php echo tgl_pecah($r->tanggal);?></td>

                <th>Pegawai :</th>
                <td><?php echo $r->nama_pegawai;?></td>
              </tr>
              <tr>
                <th>Tahun Gaji :</th>
                <td><?php echo $r->tahun;?></td>

                <th>Jabatan - Gaji :</th>
                <td><?php echo $r->jabatan." - ".uang($r->nominal);?></td>
              </tr>
              <tr>
                <th>Bulan Gaji :</th>
                <td><?php echo month_to_bulan($r->bulan);?></td>

                <th>Alamat :</th>
                <td><?php echo $r->alamat;?></td>
              </tr>
              <tr>
              <th>Minggu Ke :</th>
                <td><?php echo $r->minggu_ke;?></td>

                <th>Keterangan :</th>
                <td><?php echo $r->keterangan;?></td>
              </tr>
            </table>
          
            
          
          <button class="btn btn-app" type="submit" id="submit">
          <i class="fa fa-save"></i> Simpan
          </button>
         
          <a href="javascript:;" id="hapusButton" class="btn btn-app" data="<?php echo $this->uri->segment(4);?>">
          <i class="fa fa-trash"></i> Hapus
          </a>
            
          </form>
          <?php
          }
          
          ?>
        
        <div class="row">
        <div class="col-md-6">
        <form role="form" action="<?php echo base_url('payroll/bayar_tambah'); ?>" method="post" autocomplete="off" enctype="multipart/form-data">
        <input type="hidden" id="id_gaji_p" value="<?php echo $r->id_gaji;?>">
        <input type="hidden" id="id_pegawai" value="<?php echo $r->id_pegawai;?>">
        <input type="hidden" name="id_gaji_slip" value="<?php echo $r->id_gaji_slip;?>">
        <div id="tagNama" class="form-group">
                  <label class="control-label" for="inputError"><i id="iconNama"></i> Pilih Jenis Gaji</label>
                  <select name="id_gaji" id="id_gaji" class="form-control" required onChange="showNominal()">
                    <option value="">Pilih*</option>
                    <?php
                    if($gaji->num_rows() > 0)
                    {
                        foreach($gaji->result() as $r){
                            echo "<option value='$r->id_gaji'>$r->nama_gaji</option>";
                        }
                    }

                    ?>
                  </select>
                  <span class="help-block" id="pesanNama"></span>
        </div>

        <div id="tagNama" class="form-group">
                  <label class="control-label" for="inputError"><i id="iconNama"></i> Qty / Absen</label>
                  <input type="number" name="jumlah" id="jumlah" class="form-control" min="1" max="99" required="true" placeholder="Jumlah" value="1" onChange="hitungGaji()" onKeyup="hitungGaji()">
                  <span class="help-block" id="pesanNama"></span>
        </div>
        
        <button class="btn btn-app" type="submit" id="submit">
          <i class="fa fa-plus"></i> Tambah Gaji
        </button>

        </div>
        <div class="col-md-6">
        <div id="tagNama" class="form-group">
                  <label class="control-label" for="inputError"><i id="iconNama"></i> Gaji Satuan</label>
                  <input type="text" name="gaji" id="gaji" class="form-control" maxlength="20" required="false" placeholder="Gaji" value="0" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this),hitungGaji()">
                  <span class="help-block" id="pesanNama"></span>
        </div>

        <div id="tagNama" class="form-group">
                  <label class="control-label" for="inputError"><i id="iconNama"></i> Total</label>
                  <input type="text" name="total" id="total" class="form-control" required="true" placeholder="Total" readonly>
                  <span class="help-block" id="pesanNama"></span>
        </div>
        
        
        
        </form>
        </div>
        </div>
        <?php
        }

        if($list->num_rows() > 0){
        ?>
        <div class="table-responsive">
        <table class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Jenis</th>
                  <th>Gaji x Jumlah</th>
                  <th>Sub Total</th>
                  <th>Hapus</th>
                </tr>
                </thead>
                <tbody>
                  <?php
                  $s = array();
                  foreach($list->result() as $d){
                  ?>
                <tr>
                  <td><?php echo $d->nama_gaji;?></td>
                  <td><?php echo uang($d->gaji_satuan)." x ".$d->qty;?></td>
                  <td><?php echo uang($s[] = $d->sub_total);?></td>
                  <td><a href="<?php echo base_url('payroll/bayar_list_hapus/'.$d->id_dgs.'/'.$d->id_gaji_slip);?>" class="btn btn-xs btn-danger item_hapus">Hapus</a></td>
                </tr>
                <?php
                }
                ?>
                <tr>
                  <td colspan="2"><strong>Total</strong></td>
                  <td><strong><?php echo uang(array_sum($s));?></strong></td>
                  <td></td>
                </tr>
                </tbody>
        </table>
        </div>
        <?php
        }
        ?>
        </div>
        <!-- /.box-body -->
        
        
      </div>
      <!-- /.row -->
    </section>