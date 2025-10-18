<script>
  function uang(b)
  { 
  var _minus = false;
  if (b<0) _minus = true;
  b = b.toString();
  b=b.replace(".","");
  b=b.replace("-","");
  c = "";
  panjang = b.length;
  j = 0;
  for (i = panjang; i > 0; i--){
     j = j + 1;
     if (((j % 3) == 1) && (j != 1)){
       c = b.substr(i-1,1) + "." + c;
     } else {
       c = b.substr(i-1,1) + c;
     }
  }
  if (_minus) c = "-" + c ;
    
  hasil = c;
  return hasil;
  }
$(document).ready(function(){
$("#bobot").select();
$('#selesaiButton').on('click',function(){
          var id=$(this).attr('data');
          $('#ModalSelesai').modal('show');
          $('[name="id_selesai"]').val(id);
      });

        
$('#hapusButton').on('click',function(){
            var id=$(this).attr('data');
            $('#ModalHapus').modal('show');
            $('[name="id_hapus"]').val(id);
        });

$('#btn_selesai').on('click',function(){
  $('#btn_selesai').prop("disabled",true);
    var id=$('#id_selesai').val();
    $.ajax({
    type : "POST",
    url  : "<?php echo base_url('pembelian/selesai/')?>",
    data : {id_selesai: id},
            success: function(data){
                    $('#ModalSelesai').modal('hide');
                    $('#btn_selesai').prop("disabled",false);
                    location.reload();
            }
        });
        return false;
    });

$("#form-tambah").submit(function(e) {
e.preventDefault(); // avoid to execute the actual submit of the form.
var form = $(this);
var actionUrl = form.attr('action');
var method = form.attr('method');
$.ajax({
    type: method,
    url: actionUrl,
    data: form.serialize(), // serializes the form's elements.
    success: function(data)
    {
      console.log('Submit Berhasil !');
      location.reload();
      
    },
    error: function (data) {
      console.log('Submit Error !');
    },
});
//$("#form-tambah")[0].reset();



});

$("#form-pembelian").submit(function(e) {
e.preventDefault(); // avoid to execute the actual submit of the form.
var form = $(this);
var actionUrl = form.attr('action');
var method = form.attr('method');
$.ajax({
    type: method,
    url: actionUrl,
    data: form.serialize(), // serializes the form's elements.
    success: function(data)
    {
      console.log('Submit Berhasil !');
      location.reload();
    },
    error: function (data) {
      console.log('Submit Error !');
    },
});
});


$('.grid-container').on('click','.item_bobot',function(){
            var id=$(this).attr('data');
            var bobot = $(this).attr('bobot');

            $('#ModalBobot').modal('show');
            $('[name="id_edit"]').val(id);
            $('#bobot_update').val(bobot);
            
        });

  $('#btn_hapus_bobot').on('click',function(){
  $('#btn_hapus_bobot').prop("disabled",true);
    var id=$('#id_edit').val();
    $.ajax({
    type : "POST",
    url  : "<?php echo base_url('pembelian/hapus_bobot/')?>",
    data : {id_hapus: id},
            success: function(data){
                    $('#ModalBobot').modal('hide');
                    $('#btn_hapus_bobot').prop("disabled",false);
                    location.reload();
            }
        });
        return false;
        location.reload();
    });

$("#form-update-bobot").submit(function(e) {
e.preventDefault(); // avoid to execute the actual submit of the form.
var form = $(this);
var actionUrl = form.attr('action');
var method = form.attr('method');
$.ajax({
    type: method,
    url: actionUrl,
    data: form.serialize(), // serializes the form's elements.
    success: function(data)
    {
      console.log('Submit Berhasil !');
      location.reload();
    },
    error: function (data) {
      console.log('Submit Error !');
    },
});
});


$('#ModalBobot').on('shown.bs.modal', function () {
          $('#bobot_update').focus();
});
       
});




function hitungTotal(){
var awal = $("#tonase_0").val().toString().replace(/\./g,"");
var awal = awal.replace(",",".");

var satuan = $("#harga_satuan").val().toString().replace(/\./g,"");
var satuan = satuan.replace(",",".");

var kbk = $("#harga_kbk").val().toString().replace(/\./g,"");
var kbk = kbk.replace(",",".");

$("#beli").val(uang(parseInt(awal) * parseInt(satuan)));
$("#kbk").val(uang(parseInt(awal) * parseInt(kbk)));
}
</script>
<!--MODAL SELESAI-->
<div class="modal modal-primary fade" id="ModalSelesai" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">X</span></button>
                        <h4 class="modal-title" id="myModalLabel">Konfirmasi Pembelian</h4>
                    </div>
                    <form class="form-horizontal" action="<?php echo base_url('pembelian/selesai');?>" method="post" id="form-selesai">
                    <div class="modal-body">
                                           
                            <input type="hidden" name="id_selesai" id="id_selesai" value="">
                            <div class="alert alert-default"><p>Transaksi yang diselesaikan tidak dapat di edit, lanjutkan ?</p></div>
                                         
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-default" id="btn_selesai" data="<?php echo $this->uri->segment(4); ?>">Ya, lanjutkan</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
<!--END MODAL SELESAI-->

<!--MODAL HAPUS-->
<div class="modal modal-danger fade" id="ModalHapus" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">X</span></button>
                        <h4 class="modal-title" id="myModalLabel">Konfirmasi Penghapusan</h4>
                    </div>
                    <form class="form-horizontal" action="<?php echo base_url('pembelian/hapus');?>" method="post">
                    <div class="modal-body">
                                           
                            <input type="hidden" name="id_hapus" id="id_hapus" value="">
                            <div class="alert alert-default"><p>Apakah Anda yakin mau menghapus Pembelian ini ?</p></div>
                                         
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn_hapus btn btn-default" id="btn_hapus" data="<?php echo $this->uri->segment(4); ?>">Hapus</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        <!--END MODAL HAPUS-->

  <!--MODAL BOBOT-->
<div class="modal modal-warning fade" id="ModalBobot" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">X</span></button>
                <h4 class="modal-title" id="myModalLabel">Ubah Bobot</h4>
            </div>
            <form  action="<?php echo base_url('pembelian/update');?>" method="post" id="form-update-bobot">
            <div class="modal-body">
                                    
            <input type="hidden" name="id_edit" id="id_edit" value="">
              <div id="tagNama" class="form-group">
                  <label class="control-label" for="inputError"><i id="iconNama"></i> Bobot</label>
                  <input type="number" name="bobot_update" id="bobot_update" class="form-control" placeholder="Bobot" required min="1">
                  <span class="help-block" id="pesanNama"></span>
              </div>
                                  
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-close"></i> Tutup</button>
                <button type="submit" class="btn_update btn btn-default" id="btn_update"><i class="fa fa-save"></i>Update</button>
                </form>
                <button type="button" class="btn_hapus_bobot btn btn-default pull-left" id="btn_hapus_bobot"><i class="fa fa-trash"></i> Hapus</button>
            </div>
            
        </div>
    </div>
</div>
<!--END MODAL BOBOT-->

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
          <h3 class="box-title">Data Pembelian dan Timbangan</h3>
        </div>
        <!-- /.box-header -->
    
        <div class="box-body">
            <?php
            if($pembelian->num_rows() > 0){
            foreach($pembelian->result() as $r){
              $tim = ($r->timbangan == 0) ? 50000 : $r->timbangan; 
            ?>
            <form role="form" action="<?php echo base_url('pembelian/simpan'); ?>" method="post" autocomplete="off" enctype="multipart/form-data" id="form-pembelian">
            <input type="hidden" name="id" value="<?php echo $r->id_pembelian;?>">
            <div class="table-responsive">
            <table class="table">
              <tr>
                <th style="width:40%">Tanggal :</th>
                <td><?php echo tgl_pecah($r->tanggal);?></td>

                <th>Pengirim :</th>
                <td><?php echo $r->nama_pengirim;?></td>
              </tr>
              <tr>
                <th>No Truk :</th>
                <td><?php echo $r->no_polisi;?></td>

                <th>Status :</th>
                <td><span class="badge <?php if($r->status == "Selesai") {echo 'btn-primary' ;} else {echo 'btn-warning' ;} ?>"><?php echo $r->status;?></span></td>
              </tr>
            </table>

        <div id="tagNama" class="form-group">
                  <label class="control-label" for="inputError"><i id="iconNama"></i> Total Tonase</label>
                  <input type="text" name="tonase_0" id="tonase_0" class="form-control" readonly placeholder="Total Tonase Sebelumnya" value="<?php echo (int) $r->total_tonase;?>" >
                  <span class="help-block" id="pesanNama"></span>
        </div>

        <div class="row">
        <div class="col-md-6">
        
        <div id="tagNama" class="form-group">
                  <label class="control-label" for="inputError"><i id="iconNama"></i> Harga Satuan Beli</label>
                  <input type="text" name="harga_satuan" id="harga_satuan" class="form-control" required placeholder="Total Tonase Sebelumnya" 
                  value="<?php echo (int) $r->harga_satuan;?>" onKeyup="hitungTotal()">
                  <span class="help-block" id="pesanNama"></span>
        </div>

        <div id="tagNama" class="form-group">
                  <label class="control-label" for="inputError"><i id="iconNama"></i> Harga Satuan KBK</label>
                  <input type="text" name="harga_kbk" id="harga_kbk" class="form-control" required placeholder="Total Tonase Sebelumnya" 
                  value="<?php echo (int) $r->harga_kbk;?>" onKeyup="hitungTotal()">
                  <span class="help-block" id="pesanNama"></span>
        </div>
        </div>

        <div class="col-md-6">
        <div id="tagNama" class="form-group">
                  <label class="control-label" for="inputError"><i id="iconNama"></i> Total Harga Beli</label>
                  <input type="text" name="beli" id="beli" class="form-control" readonly placeholder="Total Tonase Sebelumnya" 
                  value="<?php echo uang($r->total_tonase * $r->harga_satuan);?>" >
                  <span class="help-block" id="pesanNama"></span>
        </div>

        <div id="tagNama" class="form-group">
                  <label class="control-label" for="inputError"><i id="iconNama"></i> Total Harga KBK</label>
                  <input type="text" name="kbk" id="kbk" class="form-control" readonly placeholder="Total Tonase Sebelumnya" 
                  value="<?php echo uang($r->total_tonase * $r->harga_kbk);?>" >
                  <span class="help-block" id="pesanNama"></span>
        </div>
        </div>

        </div>
        <div id="tagNama" class="form-group">
                  <label class="control-label" for="inputError"><i id="iconNama"></i> Harga Timbangan</label>
                  <input type="number" name="timbangan" id="timbangan" class="form-control" required="true" placeholder="Harga Timbangan" value="<?php echo (int) $r->timbangan;?>">
                  <span class="help-block" id="pesanNama"></span>
        </div>
            
          
          <button type="submit" class="btn btn-app">
          <i class="fa fa-save"></i> Simpan
          </button>
         
          <a href="javascript:;" id="hapusButton" class="btn btn-app" data="<?php echo $this->uri->segment(4);?>">
          <i class="fa fa-trash"></i> Hapus
          </a>
          <?php if($r->status == "Proses" && $r->harga_satuan > 0 && $r->harga_kbk > 0) {

          ?>
          <a href="javascript:;" class="btn btn-app" data="<?php echo $this->uri->segment(4);?>" 
          <?php if($r->harga_satuan > 0 && $r->harga_kbk > 0){echo "id='selesaiButton'";} else { echo "disabled";} ?> >
          <i class="fa fa-check"></i> Selesai
          </a>
          <?php } else if($r->status == "Selesai") { ?>
          <a href="<?php echo base_url('transaksi/pembelian/cetak-nota/'.$r->id_pembelian);?>" class="btn btn-app" target="_blank">
          <i class="fa fa-print"></i> Cetak
          </a>
          <?php } ?>
          <a href="<?php echo base_url('transaksi/pembelian');?>" class="btn btn-app">
          <i class="fa fa-arrow-left"></i> Kembali
          </a>
          </form>


          <?php
          }
          
          ?>
          
          <?php if($r->status == "Proses") {
          
          ?>
        
        <form role="form" action="<?php echo base_url('pembelian/tambah'); ?>" method="post" autocomplete="off" id="form-tambah">
        <input type="hidden" name="id_pembelian" value="<?php echo $r->id_pembelian;?>">
        <div id="tagNama" class="form-group">
                  <label class="control-label" for="inputError"><i id="iconNama"></i> Bobot</label>
                  <input type="number" name="bobot" id="bobot" class="form-control" required="true" placeholder="Bobot" min="1" max="9999999" value="0">
                  <span class="help-block" id="pesanNama"></span>
        </div>
        
        <button class="btn btn-app" type="submit" id="btn_bobot">
          <i class="fa fa-plus"></i> Tambah Bobot
        </button>

        
        <div id="tagNama" class="form-group">
                  <label class="control-label" for="inputError"><i id="iconNama"></i> Total Tonase Keseluruhan</label>
                  <input type="number" name="tonase_1" id="tonase_1" class="form-control" readonly placeholder="Total Tonase Keseluruhan" value="0">
                  <span class="help-block" id="pesanNama"></span>
        </div>
        
        </form>
        </div>
        <?php
          }
        

        #if($timbangan->num_rows() > 0){
          $jumlah_data = $timbangan->num_rows();
          $jumlah_baris = ceil($jumlah_data / 10);
        ?>
<style>
.grid-container {
  display: grid;
  grid-template-columns: 3fr 3fr 3fr 3fr 3fr 3fr 3fr 3fr 3fr 3fr;
  grid-template-rows: 30px 30px 30px 30px 30px 30px 30px 30px 30px 30px;
}
.grid-container > div {
  border: 1px solid black;
  text-align: center;
  font-size: 15px;
}
/* .grid {
    display: grid;
    grid-template-columns: repeat(5, lfr);
    border-top: 1px solid black;
    border-right: 1px solid black;
}

.grid > span {
    padding: 8px 4px;
    border-left: 1px solid black;
    border-bottom: 1px solid black;
} */
</style>


<div class="grid-container">
  <?php 
  $b = array();
  foreach($timbangan->result() as $m){
    if($r->status == "Proses"){
  ?>
  <div><a href="javascript:;" class="item_bobot" data="<?php echo $m->id_pt;?>" bobot="<?php echo (int)$m->bobot;?>"><strong><?php echo (int) $b[] = $m->bobot;?></strong></a></div>
  <?php } else { ?>
    <div><strong><?php echo (int) $b[] = $m->bobot;?></strong></div>
  <?php }
  
}
  
  ?>
</div>
<table class="table table-bordered table-striped">
    <tr>
      <td><strong>Total Tonase</strong></td>
      <td><strong><?php echo array_sum($b);?></strong></td>
    </tr>
    <tr>
      <td><strong>Total Pembelian (Harga Satuan x Tonase)</strong></td>
      <td><strong><?php echo uang($r->total_tonase * $r->harga_satuan);?></strong></td>
    </tr>
    <tr>
      <td><strong>Total KBK (Harga Satuan KBK x Tonase) + Timbangan (<?php echo uang($tim);?>)</strong></td>
      <td><strong><?php echo uang(($r->total_tonase * $r->harga_kbk) + $tim);?></strong></td>
    </tr>
    <tr>
      <td><strong>Jumlah Sak (x2)</strong></td>
      <td><strong><?php echo $timbangan->num_rows() * 2;?> Sak</strong></td>
    </tr>
  </table>
        <?php
            }
        ?>
        </div>
        <!-- /.box-body -->
        
        
      </div>
      <!-- /.row -->
    </section>