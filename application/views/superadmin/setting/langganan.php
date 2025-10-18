<script type="text/javascript">
  
$(document).ready(function(){
  var table = $('#tabel-langganan').DataTable({
     "language": {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Indonesian.json"
        },
        "processing": true,
        "serverSide": true,
        "order": [],
        "ajax": {
              url:"<?php echo base_url('setting/langganan_tampil');?>",
              type:"POST",
        },
        "columnDefs": [ {
            "targets": 0,
            "orderable":false,
            "searchable":false
        },
      {
            "targets": 4,
            "orderable":false,
            "searchable":false
      }],

    });

//GET HAPUS
$('#tabel-langganan').on('click','.item_renew',function(){
            var id=$(this).attr('data');
            $('#ModalRenew').modal('show');
            $('[name="id_renew"]').val(id);
        });

        //Hapus Langganan
        $('#btn_renew').on('click',function(){
          $('#btn_renew').prop("disabled",true);
            var id=$('#id_renew').val();
            $.ajax({
            type : "POST",
            url  : "<?php echo base_url('setting/langganan_renew/')?>",
            data : {id: id},
                    success: function(data){
                            $('#ModalRenew').modal('hide');
                            table.ajax.reload();
                            $('#btn_renew').prop("disabled",false);
                    }
                });
                return false;
            });
});
</script>
  <section class="content-header">
      <h1>
        Langganan
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-cog"></i> Setting</a></li>
        <li class="active">Langganan</li>
      </ol>
    </section>



    <section class="content">

<div class="box">
  <!--Tombol Tambah Data-->

            <div class="box-header">
              <h3 class="box-title">Data Langganan</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="table-responsive">
              
              <table id="tabel-langganan" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>No</th>
                  <th>Provider</th>
                  <th>Email</th>
                  <th>Masa Berlaku</th>
                  <th>Aksi</th>
                </tr>
                </thead>
                <tbody>
                
                </tbody>
              </table>
            </div>
            <!--MODAL HAPUS-->
        <div class="modal modal-primary fade" id="ModalRenew" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">X</span></button>
                        <h4 class="modal-title" id="myModalLabel">Konfirmasi Perpanjangan</h4>
                    </div>
                    <form class="form-horizontal">
                    <div class="modal-body">
                                           
                            <input type="hidden" name="id_renew" id="id_renew" value="">
                            <div class="alert alert-default"><p>Apakah Anda yakin mau memperpanjang langganan 1 Bulan ?</p></div>
                                         
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                        <button class="btn_renew btn btn-default" id="btn_renew">Perpanjang</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        <!--END MODAL HAPUS-->

           

            <!-- /.box-body -->
          </div>
          </section>