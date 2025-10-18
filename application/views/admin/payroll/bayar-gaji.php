<script type="text/javascript">
  
$(document).ready(function(){
  var table = $('#tabel-slip').DataTable({
     "language": {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Indonesian.json"
        },
        "processing": true,
        "serverSide": true,
        "order": [],
        "ajax": {
              url:"<?php echo base_url('payroll/slip_tampil');?>",
              type:"POST",
        },
        "columnDefs": [ {
            "targets": 0,
            "orderable":false,
            "searchable":false
        },
      {
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
            "orderable":true,
            "searchable":true
      },
      {
            "targets": 5,
            "orderable":true,
            "searchable":true
      },
      {
            "targets": 6,
            "orderable":false,
            "searchable":false
      },
      {
            "targets": 7,
            "orderable":false,
            "searchable":false
      }],

    });

//GET HAPUS
$('#tabel-slip').on('click','.item_hapus',function(){
            var id=$(this).attr('data');
            var nama=$(this).attr('nama');
            var tahun=$(this).attr('tahun');
            var bulan=$(this).attr('bulan');
            var minggu=$(this).attr('minggu');
            $('#ModalHapus').modal('show');
            $('[name="id_hapus"]').val(id);
            $('#nama').html(nama);
            $('#tahun').html(tahun);
            $('#bulan').html(bulan);
            $('#minggu').html(minggu);
        });

$('#tabel-slip').on('click','.item_selesai',function(){
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
            url  : "<?php echo base_url('payroll/bayar_hapus/')?>"+id,
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
            url  : "<?php echo base_url('payroll/bayar_selesai/')?>"+id,
            data : {id_selesai: id},
                    success: function(data){
                            $('#ModalSelesai').modal('hide');
                            table.ajax.reload();
                            $('#btn_selesai').prop("disabled",false);
                    }
                });
                return false;
            });
});
</script>
  <section class="content-header">
      <h1>
        Slip Gaji
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-money"></i> Payroll</a></li>
        <li class="active">Bayar Gaji</li>
      </ol>
    </section>



    <section class="content">

<a class="btn btn-app" href="<?php echo base_url('payroll/bayar-gaji/input');?>">
<i class="fa fa-plus"></i> Buat Slip Baru
</a>



<div class="box">
  <!--Tombol Tambah Data-->

            <div class="box-header">
              <h3 class="box-title">Daftar Slip Gaji</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="table-responsive">
              
              <table id="tabel-slip" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>No</th>
                  <th>No Slip</th>
                  <th>Tahun Bulan</th>
                  <th>Minggu Ke</th>
                  <th>Pegawai</th>
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
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">X</span></button>
                        <h4 class="modal-title" id="myModalLabel">Konfirmasi Hapus Slip</h4>
                    </div>
                    <form class="form-horizontal">
                    <div class="modal-body">
                                           
                            <input type="hidden" name="id_hapus" id="id_hapus" value="">
                            <div class="alert alert-default"><p>Apakah Anda yakin mau menghapus Slip Gaji <span id="nama"></span> Tahun <span id="tahun"></span> Bulan <span id="bulan"></span> Minggu Ke <span id="minggu"></span> ?</p></div>
                                         
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
                            <div class="alert alert-default"><p>Selesaikan Slip Gaji Ini ?</p></div>
                                         
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