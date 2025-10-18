<script>
  function showKategori(){
  var lap = $("#pengirim_id").val();
  if(lap == "*"){
  
    $("#pengirim-html").html('');

  }
  else{

    $.ajax({
    url:"<?php echo base_url('laporan/get_pengirim')?>",
    type:"GET",
    data:{},
    success:function(response){
        $("#pengirim-html").html(response);
        var batas = 10;
        $('[name = "pengirim[]"]').select2({
      minimumInputLength: 2,
      multiple:true,
      language: {
      inputTooShort: function() {
      return 'Ketik minimum 2 huruf';}
              },
      allowClear: true,
      placeholder: 'Masukkan Nama Pengirim' ,
      ajax: {
        dataType: 'json',
        url:'<?= base_url('pembelian/pengirim_cari') ?>',
        delay: 100,
        data: function(params) {
          return {
            'cari': params.term || "",
            'page': params.page || 1,
            'batas':batas
          }
        },
        processResults: function (response, params) {
        params.page = params.page || 1;
        return {
        results: response.pengirim,
        pagination:{ more:(params.page * batas) < response.count_filtered}
        };
      },
      cache: true
    }
    });
    },
    error:function(){  console.log('ERROR showProduk'); }
  });


  }
}
</script>


<section class="content-header">
      <h1>
        Laporan Pembelian
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-file-text-o"></i> Laporan</a></li>
        <li class="active">Laporan Pembelian</li>
      </ol>
    </section>

    <section class="content">
      <div class="box box-warning">
   
        <div class="box-header with-border">
          <h3 class="box-title">Masukkan Data</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <form role="form" action='<?php echo base_url('laporan/laporan-pembelian-tampil');?>' method='post' target='_blank' autocomplete="off">
           <div id="tagNama" class="form-group">
                  <label class="control-label" for="inputError"><i id="iconNama"></i> Pengirim</label>
                  <select name="id_pengirim" id="pengirim_id" class="form-control"  required onchange="showKategori()">
                      <option value="*">Semua Pengirim</option>
                      <option value="-">Beberapa Pengirim</option>
                  </select>
                  <span class="help-block" id="pesanNama"></span>
            </div>

            <div id="tagNama" class="form-group">
            <div id="pengirim-html"></div>
            </div>

            <div id="tagNama" class="form-group">
                  <label class="control-label" for="inputError"><i id="iconNama"></i> Dari Tanggal</label>
                  <input type="text" name="dari" id="tgl" data-date-format="dd-mm-yyyy" data-date-end-date="0d" class="form-control" maxlength="20" required="true" placeholder="Tanggal" value="<?php echo date("d-m-Y");?>" required>
                  <span class="help-block" id="pesanNama"></span>
            </div>

            <div id="tagNama" class="form-group">
                  <label class="control-label" for="inputError"><i id="iconNama"></i> Sampai Tanggal</label>
                  <input type="text" name="sampai" id="datepicker" data-date-format="dd-mm-yyyy" data-date-end-date="0d" class="form-control" maxlength="20" required="true" placeholder="Tanggal" value="<?php echo date("d-m-Y");?>" required>
                  <span class="help-block" id="pesanNama"></span>
            </div>


           
          
          <button class="btn btn-app" type="submit" id="submit">
          <i class="fa fa-file-text-o"></i> Tampilkan
          </button>
         
          <button class="btn btn-app" type="reset">
          <i class="fa fa-refresh"></i> Ulangi
          </button>
            
          </form>
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.row -->
    </section>