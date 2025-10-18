<script type="text/javascript">
  
$(document).ready(function(){
  var table = $('#tabel').DataTable({
     "language": {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Indonesian.json"
        },
        "processing": true,
        "serverSide": true,
        "order": [],
        "ajax": {
              url:"<?php echo base_url('akun/rekening_tampil');?>",
              type:"GET",
        },
        "columnDefs": [ {
            "targets": 0,
            "orderable":false,
            "searchable":false
        },{
            "targets": 1,
            "orderable":true,
            "searchable":true
        },
        {
                "targets": 2,
                "orderable":true,
                "searchable":true
        },
        {
                "targets": 3,
                "orderable":true,
                "searchable":true
        },
        {
                "targets": 4,
                "orderable":false,
                "searchable":false
        },
        {
                "targets": 5,
                "orderable":false,
                "searchable":false
        }],

    });


//Input Rekening
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
      table.ajax.reload(null, false);
    },
    error: function (data) {
      console.log('Submit Error !');
    },
});
$("#form-tambah")[0].reset();
$("#modal-input").modal('hide');

});

//Edit Rekening
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
   var id_bank=$(this).attr('id_bank');
   var nama_rek=$(this).attr('nama_rek');
   var no_rek=$(this).attr('no_rek');

   $('[name="id_update"]').val(id);
   $('[name="id_bank_update"]').val(id_bank).change();
   $('[name="nama_rek_update"]').val(nama_rek);
   $('[name="no_rek_update"]').val(no_rek);
   $('#modal-edit').modal('show');
});

//HAPUS Rekening
$('#btn_hapus').on('click',function(){
    $('#btn_hapus').prop("disabled",true);
    var id=$('#id_hapus').val();
    $.ajax({
    type : "POST",
    url  : "<?php echo base_url('akun/rekening_hapus/')?>",
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
        Data Rekening
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-navicon"></i> Data</a></li>
        <li class="active">Data Rekening</li>
      </ol>
    </section>



<section class="content">

<a class="btn btn-app" href="#" data-toggle="modal" data-target="#modal-input">
<i class="fa fa-plus"></i> Tambah Rekening
</a>

<div class="modal fade" id="modal-input">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-label="Close">
<span aria-hidden="true">&times;</span></button>
<h4 class="modal-title">Form Input Rekening</h4>
</div>
<form action="<?php echo base_url('akun/rekening_simpan') ;?>" method="post" id="form-tambah">
<div class="modal-body">

              <div id="tagNama" class="form-group">
                  <label class="control-label" for="inputError"><i id="iconNama"></i> Pilih Bank</label>
                  <select name="id_bank"  class="form-control" id="id_bank" required>
                    <?php
                    if($banks->num_rows() > 0){
                      foreach($banks->result() as $bank){
                          echo "<option value='$bank->id_bank'>$bank->nama_bank</option>";
                      }
                    }
                    ?>
                  </select>
                  <span class="help-block" id="pesanNama"></span>
              </div>
              
              <div id="tagNama" class="form-group">
                  <label class="control-label" for="inputError"><i id="iconNama"></i> Nama Rekening</label>
                  <input type="text" name="nama" id="nama" class="form-control" placeholder="Nama Rekening" required>
                  <span class="help-block" id="pesanNama"></span>
              </div>

              <div id="tagNama" class="form-group">
                  <label class="control-label" for="inputError"><i id="iconNama"></i> No Rekening</label>
                  <input type="text" name="no" id="no" class="form-control" placeholder="No Rekening" required>
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


<div class="box">
  <!--Tombol Tambah Data-->

            <div class="box-header">
              <h3 class="box-title">Daftar Rekening</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="table-responsive">
              
              <table id="tabel" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>No</th>
                  <th>Bank</th>
                  <th>Nama Rekening</th>
                  <th>No Rekening</th>
                  <th>Logo</th>
                  <th>Status</th>
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
                        <h4 class="modal-title" id="myModalLabel">Hapus Rekening</h4>
                    </div>
                    <form class="form-horizontal">
                    <div class="modal-body">
                                           
                            <input type="hidden" name="id_hapus" id="id_hapus" value="">
                            <div class="alert alert-default"><p>Apakah Anda yakin mau menghapus Rekening <span id="nama-hapus"></span> ?</p></div>
                                         
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
                        <h4 class="modal-title" id="myModalLabel">Form Edit Rekening</h4>
                    </div>
                    <form action="<?php echo base_url('akun/rekening_update') ;?>" method="post" id="form-edit">
<div class="modal-body">
<input type="hidden" name="id_update">
              <div id="tagNama" class="form-group">
                  <label class="control-label" for="inputError"><i id="iconNama"></i> Pilih Bank</label>
                  <select name="id_bank_update" id="id_bank_update" class="form-control" required>
                    <?php
                    if($banks->num_rows() > 0){
                      foreach($banks->result() as $bank){
                          echo "<option value='$bank->id_bank'>$bank->nama_bank</option>";
                      }
                    }
                    ?>
                  </select>
                  <span class="help-block" id="pesanNama"></span>
              </div>
              
              <div id="tagNama" class="form-group">
                  <label class="control-label" for="inputError"><i id="iconNama"></i> Nama Rekening</label>
                  <input type="text" name="nama_rek_update" id="nama_rek_update" class="form-control" placeholder="Nama Rekening" required>
                  <span class="help-block" id="pesanNama"></span>
              </div>

              <div id="tagNama" class="form-group">
                  <label class="control-label" for="inputError"><i id="iconNama"></i> No Rekening</label>
                  <input type="text" name="no_rek_update" id="no_rek_update" class="form-control" placeholder="No Rekening" required>
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