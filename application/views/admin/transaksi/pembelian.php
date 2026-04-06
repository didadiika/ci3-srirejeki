<script type="text/javascript">
  
$(document).ready(function(){

$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
    $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
});
  var table_ketan = $('#tabel-ketan').DataTable({
     "language": {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Indonesian.json"
        },
        "processing": true,
        "serverSide": true,
        "order": [],
        autoWidth: false,
        scrollX: true,
        "ajax": {
              url:"<?php echo base_url('pembelian/tampil');?>",
              type:"GET",
        },
        "columnDefs": [ {
            "targets": 0,
            "orderable":false,
            "searchable":false
        },
      {
            "targets": 10,
            "orderable":false,
            "searchable":false
      }],
      

    });

  var table_ir = $('#tabel-ir').DataTable({
     "language": {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Indonesian.json"
        },
        "processing": true,
        "serverSide": true,
        autoWidth: false,
        scrollX: true,
        "order": [],
        "ajax": {
              url:"<?php echo base_url('pembelian/tampil_ir');?>",
              type:"GET",
        },
        "columnDefs": [ {
            "targets": 0,
            "orderable":false,
            "searchable":false
        },
      {
            "targets": 10,
            "orderable":false,
            "searchable":false
      }],

    });


//Buat Pembelian
$("#form-buat").submit(function(e) {
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
      table_ketan.ajax.reload(null, false);
      table_ir.ajax.reload(null, false);
    },
    error: function (data) {
      console.log('Submit Error !');
    },
});
$("#form-buat")[0].reset();
$("#modal-buat").modal('hide');

});


//GET HAPUS
$('#tabel-ketan').on('click','.item_hapus',function(){
            var id=$(this).attr('data');
            var nama=$(this).attr('nama');
            $('#ModalHapus').modal('show');
            $('[name="id_hapus"]').val(id);
            $('#nama').html(nama);
        });

$('#tabel-ir').on('click','.item_hapus',function(){
            var id=$(this).attr('data');
            var nama=$(this).attr('nama');
            $('#ModalHapus').modal('show');
            $('[name="id_hapus"]').val(id);
            $('#nama').html(nama);
        });

$('#tabel-ketan').on('click','.item_selesai',function(){
            var id=$(this).attr('data-selesai');
            $('#ModalSelesai').modal('show');
            $('[name="id_selesai"]').val(id);
});

$('#tabel-ir').on('click','.item_selesai',function(){
            var id=$(this).attr('data-selesai');
            $('#ModalSelesai').modal('show');
            $('[name="id_selesai"]').val(id);
});

        //Hapus Barang
        $('#btn_hapus').on('click',function(){
          $('#btn_hapus').prop("disabled",true);
            var id=$('#id_hapus').val();
            $.ajax({
            type : "POST",
            url  : "<?php echo base_url('pembelian/hapus/')?>"+id,
            data : {id_hapus: id},
                    success: function(data){
                            $('#ModalHapus').modal('hide');
                            table_ketan.ajax.reload();
                            table_ir.ajax.reload();
                            $('#btn_hapus').prop("disabled",false);
                    }
                });
                return false;
            });


          $('#btn_selesai').on('click',function(){
          $('#btn_selesai').prop("disabled",true);
            var id=$('#id_selesai').val();
            $.ajax({
            type : "POST",
            url  : "<?php echo base_url('pembelian/selesai/')?>"+id,
            data : {id_selesai: id},
                    success: function(data){
                            $('#ModalSelesai').modal('hide');
                            table_ketan.ajax.reload();
                            table_ir.ajax.reload();
                            $('#btn_selesai').prop("disabled",false);
                    }
                });
                return false;
            });


      /*------------------------------PEMBAYARAN----------------------------*/

      $('#tabel-ketan').on('click','.item_bayar',function(){
                  $("#form-bayar")[0].reset();
                  var id = $(this).attr('data');
                  var tgl = $(this).attr('tanggal-pembelian');
                  var tot = $(this).attr('total-pembelian');
                  var peng = $(this).attr('pengirim');
                  var dibayar = $(this).attr('dibayar');
                  var sisa = $(this).attr('sisa');

                  $('#modal-input-bayar').modal('show');
                  $('[name="id_bayar"]').val(id);
                  $('[name="tanggal_pembelian"]').val(tgl);
                  $('[name="pengirim"]').val(peng);
                  $('[name="total_pembelian"]').val(uang(tot));
                  $('[name="sudah_dibayar"]').val(uang(dibayar));
                  $('[name="sisa"]').val(uang(sisa));

                  if (AutoNumeric.getAutoNumericElement('[name="bayar"]') === null) {
                        const autoNumericOptionsRp = {
                          allowDecimalPadding        : false, 
                          currencySymbol             : '',
                          currencySymbolPlacement    : 'p',
                          decimalCharacter           : ',',
                          digitGroupSeparator        : '.',
                          emptyInputBehavior         : 'focus',
                          maximumValue               : sisa
                      };
                        mata = new AutoNumeric('[name="bayar"]', autoNumericOptionsRp);
                      }
                      else
                      {
                        
                        AutoNumeric.set('[name="bayar"]','0');
                        mata.update({ maximumValue : sisa });
                      }
      });

      $('#tabel-ir').on('click','.item_bayar',function(){
                  $("#form-bayar")[0].reset();
                  var id = $(this).attr('data');
                  var tgl = $(this).attr('tanggal-pembelian');
                  var tot = $(this).attr('total-pembelian');
                  var peng = $(this).attr('pengirim');
                  var dibayar = $(this).attr('dibayar');
                  var sisa = $(this).attr('sisa');

                  $('#modal-input-bayar').modal('show');
                  $('[name="id_bayar"]').val(id);
                  $('[name="tanggal_pembelian"]').val(tgl);
                  $('[name="pengirim"]').val(peng);
                  $('[name="total_pembelian"]').val(uang(tot));
                  $('[name="sudah_dibayar"]').val(uang(dibayar));
                  $('[name="sisa"]').val(uang(sisa));

                  if (AutoNumeric.getAutoNumericElement('[name="bayar"]') === null) {
                        const autoNumericOptionsRp = {
                          allowDecimalPadding        : false, 
                          currencySymbol             : '',
                          currencySymbolPlacement    : 'p',
                          decimalCharacter           : ',',
                          digitGroupSeparator        : '.',
                          emptyInputBehavior         : 'focus',
                          maximumValue               : sisa
                      };
                        mata = new AutoNumeric('[name="bayar"]', autoNumericOptionsRp);
                      }
                      else
                      {
                        
                        AutoNumeric.set('[name="bayar"]','0');
                        mata.update({ maximumValue : sisa });
                      }
      });


      $("#form-bayar").submit(function(e) {
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
            table_ketan.ajax.reload(null, false);
            table_ir.ajax.reload(null, false);
          },
          error: function (data) {
            console.log('Submit Error !');
          },
      });
      $("#form-bayar")[0].reset();
      $("#modal-input-bayar").modal('hide');
      });

      $('#tabel-ketan').on('click','.item_riwayat',function(){
                  var id = $(this).attr('data');

                  $("#printSection-history").load('<?php echo base_url('transaksi/pembelian/riwayat-pembayaran');?>/'+id);
                  $('#modal-riwayat-bayar').modal('show');

      });


      /*------------------------------PEMBAYARAN----------------------------*/


});



</script>
  <section class="content-header">
      <h1>
        Pembelian
      </h1>
      <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-refresh"></i> Transaksi</a></li>
        <li class="active">Pembelian</li>
      </ol>
    </section>



    <section class="content">

    <a class="btn btn-app" href="#" data-toggle="modal" data-target="#modal-buat">
<i class="fa fa-plus"></i> Tambah Pembelian
</a>


<!----------------------------------MODAL BAYAR------------------------------------------------------------>
<div class="modal fade" id="modal-input-bayar">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-label="Close">
<span aria-hidden="true">&times;</span></button>
<h4 class="modal-title">Form Input Pembayaran</h4>
</div>
<form action="<?php echo base_url('pembelian/simpan_pembayaran') ;?>" method="post" id="form-bayar" autocomplete="off">
<div class="modal-body">

      <input type="hidden" name="id_bayar">

      <div class="row">
        <div class="col-md-6">
        <div id="tagNama" class="form-group">
            <label class="control-label" for="inputError"><i id="iconNama"></i> Tanggal Pembelian</label>
            <input type="text" name="tanggal_pembelian"  class="form-control" placeholder="Tanggal Pembelian" readonly>
            <span class="help-block" id="pesanNama"></span>
        </div>
        </div>

        <div class="col-md-6">
        <div id="tagNama" class="form-group">
          <label class="control-label" for="inputError"><i id="iconNama"></i> Pengirim</label>
          <input type="text" name="pengirim" class="form-control" placeholder="Pengirim" readonly>
          <span class="help-block" id="pesanNama"></span>
        </div>
        </div>
        <div class="col-md-6">
            <div id="tagNama" class="form-group">
              <label class="control-label" for="inputError"><i id="iconNama"></i> Total Pembelian</label>
              <input type="text" name="total_pembelian" class="form-control" placeholder="Total" readonly>
              <span class="help-block" id="pesanNama"></span>
            </div>
        </div>

        <div class="col-md-6">  
          <div id="tagNama" class="form-group">
              <label class="control-label" for="inputError"><i id="iconNama"></i> Sudah Bayar</label>
              <input type="text" name="sudah_dibayar" class="form-control" placeholder="Sudah Bayar" readonly>
              <span class="help-block" id="pesanNama"></span>
            </div>
        </div>
      </div>

      
      <div id="tagNama" class="form-group">
              <label class="control-label" for="inputError"><i id="iconNama"></i> Sisa</label>
              <input type="text" name="sisa" class="form-control" placeholder="Sisa" readonly>
              <span class="help-block" id="pesanNama"></span>
            </div>

      <div id="tagNama" class="form-group">
          <label class="control-label" for="inputError"><i id="iconNama"></i> Pilih Tanggal Pembayaran</label>
          <input type="text" name="tanggal" id="datepicker" class="form-control" placeholder="Tanggal" required
          data-date-format="dd-mm-yyyy" value="<?php echo date("d-m-Y");?>" data-date-end-date="0d">
          <span class="help-block" id="pesanNama"></span>
      </div>

      <div id="tagNama" class="form-group">
          <label class="control-label" for="inputError"><i id="iconNama"></i> Nominal Bayar</label>
          <input type="text" name="bayar"  class="form-control" placeholder="Bayar" required>
          <span class="help-block" id="pesanNama"></span>
      </div>

</div>
<div class="modal-footer">
<button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-times"></i> Batal</button>
<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
</div>
</form>
</div>
</div>
</div>
<!----------------------------------MODAL BAYAR------------------------------------------------------------>


<!----------------------------------MODAL RIWAYAT---------------------------------------------------------->
<div class="modal fade" id="modal-riwayat-bayar">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-label="Close">
<span aria-hidden="true">&times;</span></button>
<h4 class="modal-title">Riwayat Pembayaran</h4>
</div>
<div class="modal-body">

<div id="printSection-history">
</div>

</div>


</div>
</div>
</div>
<!----------------------------------MODAL RIWAYAT---------------------------------------------------------->


<div class="modal fade" id="modal-buat">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-label="Close">
<span aria-hidden="true">&times;</span></button>
<h4 class="modal-title">Form Input Pembelian</h4>
</div>
<form action="<?php echo base_url('pembelian/buat') ;?>" method="post" id="form-buat" autocomplete="off">
<div class="modal-body">

      <div id="tagNama" class="form-group">
          <label class="control-label" for="inputError"><i id="iconNama"></i> Tanggal Pembelian</label>
          <input type="text" name="tanggal" id="datepicker" class="form-control" placeholder="Tanggal" required
          data-date-format="dd-mm-yyyy" value="<?php echo date("d-m-Y");?>" data-date-end-date="0d">
          <span class="help-block" id="pesanNama"></span>
      </div>

      <div id="tagNama" class="form-group">
          <label class="control-label" for="inputError"><i id="iconNama"></i> Pilih Kategori</label>
          <select name="id_kategori"  class="form-control" id="id_kategori" required>
            <option value="">Pilih Kategori*</option>
            <?php
            foreach ($kategori as $k) {
              echo '<option value="'.$k->id.'">'.$k->nama_kategori.'</option>';
            }

            ?>
          </select>
          <span class="help-block" id="pesanNama"></span>
      </div>

      <div id="tagNama" class="form-group">
          <label class="control-label" for="inputError"><i id="iconNama"></i> Pilih Pengirim</label>
          <select name="id_pengirim"  class="form-control select2" style="width: 100%;" id="id_pengirim" required>
          </select>
          <span class="help-block" id="pesanNama"></span>
      </div>

      <div id="tagNama" class="form-group">
          <label class="control-label" for="inputError"><i id="iconNama"></i> No Truk</label>
          <input type="text" name="no_polisi" id="no_polisi" class="form-control" placeholder="No Truk" required>
          <span class="help-block" id="pesanNama"></span>
      </div>

      <div id="tagNama" class="form-group">
          <label class="control-label" for="inputError"><i id="iconNama"></i> Timbangan Mesin</label>
          <input type="checkbox" data-toggle="toggle" name="jenis_timbangan" checked value="Ya" data-on="Ya" data-off="Tidak">
          <span class="help-block" id="pesanNama"></span>
      </div>

</div>
<div class="modal-footer">
<button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-times"></i> Batal</button>
<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
</div>
</form>
</div>

</div>

</div>


<div class="alert alert-success alert-dismissible">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      <h4><i class="icon fa fa-info"></i> Catatan</h4>
               Hanya transaksi yang ber status <strong>Selesai</strong> yang akan muncul di Laporan Pembelian dan Pembukuan Keuangan. 
</div>

<div class="box">
  <!--Tombol Tambah Data-->


   <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <?php
              $counter = 0;
              foreach ($kategori as $k) {
                if ($counter == 0) {
                  echo '<li class="active"><a href="#tab_'.$k->id.'" data-toggle="tab">'.$k->nama_kategori.'</a></li>';
                }
                else
                {
                  echo '<li><a href="#tab_'.$k->id.'" data-toggle="tab">'.$k->nama_kategori.'</a></li>';
                }
                $counter++;
              } 
              ?>
            </ul>
            <div class="tab-content">
              <?php
              $counter = 0;
              foreach ($kategori as $k) {
                  $isActive = ($counter == 0) ? 'active' : '';
                  echo '<div class="tab-pane '.$isActive.'" id="tab_'.$k->id.'">';
              ?>
              <table id="tabel-<?php echo strtolower($k->nama_kategori);  ?>" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>No</th>
                  <th>Tanggal</th>
                  <th>No Truk</th>
                  <th>Pengirim</th>
                  <th>Total Timbangan</th>
                  <th>Harga Satuan</th>                  
                  <th>Total</th>
                  <th>Status</th>
                  <th>Pembayaran</th>
                  <th>Utang</th>
                  <th>Aksi</th>
                </tr>
                </thead>
              </table>
              <?php
                  echo '</div>';
                  $counter++;
                
              }
              ?>
            </div>
    </div>



            
 <!--MODAL HAPUS-->
<div class="modal modal-danger fade" id="ModalHapus" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
<div class="modal-dialog" role="document">
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">x</span></button>
<h4 class="modal-title" id="myModalLabel">Konfirmasi Hapus Pembelian</h4>
</div>
<form class="form-horizontal">
<div class="modal-body">
    
<input type="hidden" name="id_hapus" id="id_hapus" value="">
<div class="alert alert-default"><p>Apakah Anda yakin mau menghapus Pembelian <span id="nama"></span> ?</p></div>
  
</div>
<div class="modal-footer">
<button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
<button class="btn_hapus btn btn-danger" id="btn_hapus">Hapus</button>
</div>
</form>
</div>
</div>
</div>
<!--END MODAL HAPUS-->

<!--MODAL SELESAI-->
<div class="modal modal-info fade" id="ModalSelesai" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
<div class="modal-dialog" role="document">
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">X</span></button>
<h4 class="modal-title" id="myModalLabel">Konfirmasi Penyelesaian</h4>
</div>
<form class="form-horizontal">
<div class="modal-body">

<input type="hidden" name="id_selesai" id="id_selesai" value="">
<div class="alert alert-default"><p>Selesaikan Pembelian Ini ?</p></div>

</div>
<div class="modal-footer">
<button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
<button class="btn_hapus btn btn-info" id="btn_selesai">Selesai</button>
</div>
</form>
</div>
</div>
</div>
<!--END MODAL SELESAI-->
</section>