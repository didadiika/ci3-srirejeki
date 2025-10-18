<script type="text/javascript">
  
$(document).ready(function(){
  var table = $('#tabel-maintenance').DataTable({
     "language": {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Indonesian.json"
        },
        "processing": true,
        "serverSide": true,
        "order": [],
        "ajax": {
              url:"<?php echo base_url('setting/setting_maintenance_tampil');?>",
              type:"POST",
        },
        "columnDefs": [ {
            "targets": 0,
            "orderable":false,
            "searchable":false
        },
      {
            "targets": 2,
            "orderable":false,
            "searchable":false
      }],

    });


$('#tabel-maintenance').on('click','.item_on',function(){
            $('.item_on').prop("disabled",true);
            var id=$(this).attr('data');
            $.ajax({
            type : "POST",
            url  : "<?php echo base_url('setting/setting_maintenance_on/')?>",
            data : {id: id},
                    success: function(data){
                            table.ajax.reload();
                            $('.item_on').prop("disabled",false);
                    }
                });
                return false;
        });

        $('#tabel-maintenance').on('click','.item_off',function(){
            $('.item_off').prop("disabled",true);
            var id=$(this).attr('data');
            $.ajax({
            type : "POST",
            url  : "<?php echo base_url('setting/setting_maintenance_off/')?>",
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
        Setting Maintenance
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-cog"></i> Setting</a></li>
        <li class="active">Setting Maintenance</li>
      </ol>
    </section>



    <section class="content">

<div class="box">
  <!--Tombol Tambah Data-->

            <div class="box-header">
              <h3 class="box-title">Data Setting Maintenance</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="table-responsive">
              
              <table id="tabel-maintenance" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>No</th>
                  <th>Mode Perawatan</th>
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