<script type="text/javascript">
  
$(document).ready(function(){
  var table = $('#tabel-backup').DataTable({
     "language": {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Indonesian.json"
        },
        "processing": true,
        "serverSide": true,
        "order": [],
        "ajax": {
              url:"<?php echo base_url('setting/setting_backup_tampil');?>",
              type:"POST",
        },
        "columnDefs": [ {
            "targets": 0,
            "orderable":false,
            "searchable":false
        },
      {
            "targets": 3,
            "orderable":false,
            "searchable":false
      }],

    });


$('#tabel-backup').on('click','.item_on',function(){
            $('.item_on').prop("disabled",true);
            var id=$(this).attr('data');
            $.ajax({
            type : "POST",
            url  : "<?php echo base_url('setting/setting_backup_on/')?>",
            data : {id: id},
                    success: function(data){
                            table.ajax.reload();
                            $('.item_on').prop("disabled",false);
                    }
                });
                return false;
        });

        $('#tabel-backup').on('click','.item_off',function(){
            $('.item_off').prop("disabled",true);
            var id=$(this).attr('data');
            $.ajax({
            type : "POST",
            url  : "<?php echo base_url('setting/setting_backup_off/')?>",
            data : {id: id},
                    success: function(data){
                            table.ajax.reload();
                            $('.item_off').prop("disabled",false);
                    }
                });
                return false;
        });

});
</script>
  <section class="content-header">
      <h1>
        Setting Backup
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-cog"></i> Setting</a></li>
        <li class="active">Setting Backup</li>
      </ol>
    </section>



    <section class="content">

<div class="box">
  <!--Tombol Tambah Data-->

            <div class="box-header">
              <h3 class="box-title">Data Setting Backup</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="table-responsive">
              
              <table id="tabel-backup" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>No</th>
                  <th>Auto Backup</th>
                  <th>Jumlah Hari</th>
                  <th>Aksi</th>
                </tr>
                </thead>
                <tbody>
                
                </tbody>
              </table>
            </div>

           

            <!-- /.box-body -->
          </div>
          </section>