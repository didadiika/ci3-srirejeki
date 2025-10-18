<script type="text/javascript">
const autoNumericOptions = {
    allowDecimalPadding        : false, 
    currencySymbol             : '',
    currencySymbolPlacement    : 'p',
    decimalCharacter           : ',',
    digitGroupSeparator        : '.',
    emptyInputBehavior         : 'zero',
    minimumValue               : '0',
    leadingZero                : 'deny'
};

function showDK(){

  var jenis = $("#jenis").val();

  if(jenis == "pemasukan"){
    AutoNumeric.set('[name="kredit"]',0);
    $("#kredit").prop("readonly",true);
    $("#debit").prop("readonly",false);
  } else {
    AutoNumeric.set('[name="debit"]',0);
    $("#kredit").prop("readonly",false);
    $("#debit").prop("readonly",true);
   }

}

$(document).ready(function(){
new AutoNumeric('[name="debit"]', autoNumericOptions);
new AutoNumeric('[name="kredit"]', autoNumericOptions);

  var table = $('#tabel').DataTable({
     "language": {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Indonesian.json"
        },
        "processing": true,
        "serverSide": true,
        "order": [],
        "ajax": {
              url:"<?php echo base_url('bukukeuangan/tampil');?>",
              type:"GET",
        },
        "columnDefs": [ {
            "targets": 0,
            "orderable":false,
            "searchable":false
        },
        {
                "targets": 6,
                "orderable":false,
                "searchable":false
        }],

    });


//Input Pengeluaran
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
      AutoNumeric.set('[name="debit"]',0);
      AutoNumeric.set('[name="kredit"]',0);
      $("#kredit").prop("readonly",true);
      $("#debit").prop("readonly",false);
      table.ajax.reload(null, false);
    },
    error: function (data) {
      console.log('Submit Error !');
    },
});
$("#form-tambah")[0].reset();
$("#modal-input").modal('hide');

});

//Edit Pengeluaran
$("#form-edit").submit(function(e) {
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
$("#form-edit")[0].reset();
$("#modal-edit").modal('hide');

});

//GET HAPUS
$('#tabel').on('click','.item_hapus',function(){
            var id=$(this).attr('data');
            var nama=$(this).attr('nama');
            $('#modal-hapus').modal('show');
            $('[name="id_hapus"]').val(id);
            $('#nama-hapus').html(nama);
        });
//GET EDIT
$('#tabel').on('click','.item_edit',function(){
   var id=$(this).attr('data');
   var nama=$(this).attr('nama');
   $('[name="id_update"]').val(id);
   $('[name="nama_update"]').val(nama);
   $('#modal-edit').modal('show');
});

//HAPUS PENGIRIM
$('#btn_hapus').on('click',function(){
    $('#btn_hapus').prop("disabled",true);
    var id=$('#id_hapus').val();
    $.ajax({
    type : "POST",
    url  : "<?php echo base_url('bukukeuangan/hapus/')?>",
    data : {id: id},
            success: function(data){
                    $('#modal-hapus').modal('hide');
                    table.ajax.reload();
                    $('#btn_hapus').prop("disabled",false);
            }
        });
        return false;
    });


});
</script>
  <section class="content-header">
      <h1>
        Buku Keuangan
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-refresh"></i> Transaksi</a></li>
        <li class="active">Buku Keuangan</li>
      </ol>
    </section>



<section class="content">

<a class="btn btn-app" href="#" data-toggle="modal" data-target="#modal-input">
<i class="fa fa-plus"></i> Tambah Catatan Kas
</a>




<div class="modal fade" id="modal-input">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-label="Close">
<span aria-hidden="true">&times;</span></button>
<h4 class="modal-title">Form Input Kas</h4>
</div>
<form action="<?php echo base_url('bukukeuangan/simpan') ;?>" method="post" id="form-tambah">
<div class="modal-body">

              <div id="tagNama" class="form-group">
                  <label class="control-label" for="inputError"><i id="iconNama"></i> Tanggal</label>
                  <input type="text" name="tanggal" id="datepicker" class="form-control" placeholder="Tanggal" required autocomplete="off" data-date-format="dd-mm-yyyy" data-date-end-date="0d">
                  <span class="help-block" id="pesanNama"></span>
              </div>

              <div id="tagNama" class="form-group">
                  <label class="control-label" for="inputError"><i id="iconNama"></i> Keterangan</label>
                  <textarea name="keterangan" id="keterangan" class="form-control" placeholder="Keterangan" required></textarea>
                  <span class="help-block" id="pesanNama"></span>
              </div>

              <div id="tagNama" class="form-group">
                  <label class="control-label" for="inputError"><i id="iconNama"></i> Jenis</label>
                  <select name="jenis" id="jenis" class="form-control" required onChange="showDK()">
                    <option value="pemasukan" selected>Pemasukan</option>
                    <option value="pengeluaran">Pengeluaran</option>
                  </select>
                  <span class="help-block" id="pesanNama"></span>
              </div>

              <div class="row">
              <div class="col-md-6">

              <div id="tagNama" class="form-group">
                  <label class="control-label" for="inputError"><i id="iconNama"></i> Debit</label>
                  <input type="text" name="debit" id="debit" class="form-control" placeholder="Debit" required value="0">
                  <span class="help-block" id="pesanNama"></span>
              </div>
              </div>

              <div class="col-md-6">
              <div id="tagNama" class="form-group">
                  <label class="control-label" for="inputError"><i id="iconNama"></i> Kredit</label>
                  <input type="text" name="kredit" id="kredit" class="form-control" placeholder="Kredit" required value="0" readonly>
                  <span class="help-block" id="pesanNama"></span>
              </div>

              </div>
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


<div class="box">
  <!--Tombol Tambah Data-->

            <div class="box-header">
              <h3 class="box-title">Daftar Transaksi Kas</h3>
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
                  <th>Debit</th>
                  <th>Kredit</th>
                  <th>Jenis</th>
                  <th>Aksi</th>
                </tr>
                </thead>
                <tbody>
                
                </tbody>
              </table>
            </div>
        <!--MODAL HAPUS-->
        <div class="modal modal-danger fade" id="modal-hapus" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">X</span></button>
                        <h4 class="modal-title" id="myModalLabel">Hapus Transaksi</h4>
                    </div>
                    <form class="form-horizontal">
                    <div class="modal-body">
                                           
                            <input type="hidden" name="id_hapus" id="id_hapus" value="">
                            <div class="alert alert-default"><p>Apakah Anda yakin mau menghapus Transaksi <span id="nama-hapus"></span> ?</p></div>
                                         
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default  pull-left" data-dismiss="modal">Tutup</button>
                        <button class="btn_hapus btn btn-danger" id="btn_hapus">Hapus</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        <!--END MODAL HAPUS-->

        <!--MODAL EDIT-->
        <div class="modal fade" id="modal-edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">X</span></button>
                        <h4 class="modal-title" id="myModalLabel">Form Edit Transaksi Kas</h4>
                    </div>
                    <form action="<?php echo base_url('data/pengirim_update') ;?>" method="post" id="form-edit">
<div class="modal-body">
<input type="hidden" name="id_update">
              <div id="tagNama" class="form-group">
                  <label class="control-label" for="inputError"><i id="iconNama"></i> Nama Pengeluaran</label>
                  <input type="text" name="nama_update" id="nama_update" class="form-control" placeholder="Nama Pengeluaran" required>
                  <span class="help-block" id="pesanNama"></span>
              </div>

</div>
<div class="modal-footer">
<button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-times"></i> Batal</button>
<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Update</button>
</div>
</form>
                </div>
            </div>
        </div>
        <!--END MODAL EDIT-->   

            <!-- /.box-body -->
          </div>
          </section>