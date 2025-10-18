<script type="text/javascript">

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


  var table = $('#tabel').DataTable({
     "language": {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Indonesian.json"
        },
        "processing": true,
        "serverSide": true,
        "order": [],
        "ajax": {
              url:"<?php echo base_url('gaji/tampil');?>",
              type:"GET",
        },
        "columnDefs": [ {
            "targets": 0,
            "orderable":false,
            "searchable":false
        },
        {
            "targets": 5,
            "orderable":false,
            "searchable":false
        }],

    });


//Buat Invoice
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
      table.ajax.reload(null, false);
    },
    error: function (data) {
      console.log('Submit Error !');
    },
});
$("#form-buat")[0].reset();
$("#modal-buat").modal('hide');

});


//GET HAPUS
$('#tabel').on('click','.item_hapus',function(){
            var id=$(this).attr('data');
            var nama=$(this).attr('nama');
            $('#ModalHapus').modal('show');
            $('[name="id_hapus"]').val(id);
            $('#nama').html(nama);
        });

$('#tabel').on('click','.item_selesai',function(){
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
            url  : "<?php echo base_url('gaji/hapus/')?>"+id,
            data : {id_hapus: id},
                    success: function(data){
                            $('#ModalHapus').modal('hide');
                            table.ajax.reload();
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
            url  : "<?php echo base_url('invoice/selesai/')?>"+id,
            data : {id_selesai: id},
                    success: function(data){
                            $('#ModalSelesai').modal('hide');
                            table.ajax.reload();
                            $('#btn_selesai').prop("disabled",false);
                    }
                });
                return false;
            });


/*------------------------------PEMBAYARAN----------------------------*/

$('#tabel').on('click','.item_bayar',function(){
            $("#form-bayar")[0].reset();
            var id = $(this).attr('data');
            var tgl = $(this).attr('tanggal-invoice');
            var tot = $(this).attr('total-invoice');
            var plg = $(this).attr('pelanggan');
            var dibayar = $(this).attr('dibayar');
            var sisa = $(this).attr('sisa');

            $('#modal-input-bayar').modal('show');
            $('[name="id_bayar"]').val(id);
            $('[name="tanggal_invoice"]').val(tgl);
            $('[name="pelanggan"]').val(plg);
            $('[name="total_invoice"]').val(uang(tot));
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
      table.ajax.reload(null, false);
    },
    error: function (data) {
      console.log('Submit Error !');
    },
});
$("#form-bayar")[0].reset();
$("#modal-input-bayar").modal('hide');
});

$('#tabel').on('click','.item_riwayat',function(){
            var id = $(this).attr('data');

            $("#printSection-history").load('<?php echo base_url('transaksi/invoice/riwayat-pembayaran');?>/'+id);
            $('#modal-riwayat-bayar').modal('show');

});


/*------------------------------PEMBAYARAN----------------------------*/

});



</script>
  <section class="content-header">
      <h1>
        Gaji Karyawan
      </h1>
      <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-refresh"></i> Transaksi</a></li>
        <li class="active">Gaji Karyawan</li>
      </ol>
    </section>



    <section class="content">

<a class="btn btn-app" href="#" data-toggle="modal" data-target="#modal-buat">
<i class="fa fa-plus"></i> Tambah Pembayaran Gaji
</a>
<a class="btn btn-app" href="<?php echo base_url('data/karyawan/cetak-absensi');?>" target="_blank">
<i class="fa fa-list"></i> Cetak Formulir Absensi
</a>

<!--MODAL BAYAR-->
<div class="modal fade" id="modal-input-bayar">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-label="Close">
<span aria-hidden="true">&times;</span></button>
<h4 class="modal-title">Form Input Pembayaran</h4>
</div>
<form action="<?php echo base_url('invoice/simpan_pembayaran') ;?>" method="post" id="form-bayar" autocomplete="off">
<div class="modal-body">

      <input type="hidden" name="id_bayar">

      <div class="row">
        <div class="col-md-6">
        <div id="tagNama" class="form-group">
            <label class="control-label" for="inputError"><i id="iconNama"></i> Tanggal Invoice</label>
            <input type="text" name="tanggal_invoice"  class="form-control" placeholder="Tanggal Invoice" readonly>
            <span class="help-block" id="pesanNama"></span>
        </div>
        </div>

        <div class="col-md-6">
        <div id="tagNama" class="form-group">
          <label class="control-label" for="inputError"><i id="iconNama"></i> Pelanggan</label>
          <input type="text" name="pelanggan" class="form-control" placeholder="Pelanggan" readonly>
          <span class="help-block" id="pesanNama"></span>
        </div>
        </div>
        <div class="col-md-6">
            <div id="tagNama" class="form-group">
              <label class="control-label" for="inputError"><i id="iconNama"></i> Total Invoice</label>
              <input type="text" name="total_invoice" class="form-control" placeholder="Total" readonly>
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
<!--MODAL BAYAR-->


<!--MODAL RIWAYAT-->
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
<!--MODAL RIWAYAT-->


<!--MODAL BUAT-->
<div class="modal fade" id="modal-buat">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-label="Close">
<span aria-hidden="true">&times;</span></button>
<h4 class="modal-title">Form Input Gaji Karyawan</h4>
</div>
<form action="<?php echo base_url('gaji/buat') ;?>" method="post" id="form-buat" autocomplete="off">
<div class="modal-body">

      <div id="tagNama" class="form-group">
          <label class="control-label" for="inputError"><i id="iconNama"></i> Tanggal Gaji</label>
          <input type="text" name="tanggal" id="datepicker" class="form-control" placeholder="Tanggal" required
          data-date-format="dd-mm-yyyy" value="<?php echo date("d-m-Y");?>" data-date-end-date="0d">
          <span class="help-block" id="pesanNama"></span>
      </div>

      <div id="tagNama" class="form-group">
                  <label class="control-label" for="inputError"><i id="iconNama"></i> Keterangan</label>
                  <textarea name="keterangan" id="keterangan" class="form-control" placeholder="Keterangan" required></textarea>
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
<!--MODAL BUAT-->





<div class="alert alert-success alert-dismissible">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      <h4><i class="icon fa fa-info"></i> Catatan</h4>
               Hanya Transaksi Gaji yang ber status <strong>Selesai</strong> yang bisa dicetak. 
</div>

<div class="box">
  <!--Tombol Tambah Data-->

            <div class="box-header">
              <h3 class="box-title">Daftar Invoice</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="table-responsive">
              
              <table id="tabel" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>No</th>
                  <th>Tanggal</th>
                  <th>Keterangan</th>
                  <th>Total Gaji</th>
                  <th>Status</th>
                  <th>Aksi</th>
                </tr>
                </thead>
              </table>
            </div>
            <!--MODAL HAPUS-->
        <div class="modal modal-danger fade" id="ModalHapus" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">x</span></button>
                        <h4 class="modal-title" id="myModalLabel">Konfirmasi Hapus Invoice</h4>
                    </div>
                    <form class="form-horizontal">
                    <div class="modal-body">
                                           
                            <input type="hidden" name="id_hapus" id="id_hapus" value="">
                            <div class="alert alert-default"><p>Apakah Anda yakin mau menghapus Invoice <span id="nama"></span> ?</p></div>
                                         
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
                            <div class="alert alert-default"><p>Selesaikan Invoice Ini ?</p></div>
                                         
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
           

            <!-- /.box-body -->
          </div>
          </section>