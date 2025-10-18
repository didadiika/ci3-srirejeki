<script>
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

  function hitung(){
    var harga = $("#harga").val().toString().replace(/\./g,"");
    var harga = harga.replace(",",".");

    var qty = $("#qty").val();
    var sub_total = qty * harga;

    $("#sub_total").val(uang(sub_total));
  }
$(document).ready(function(){
  <?php if($status == "Proses"){ ?>
new AutoNumeric('[name="harga"]', autoNumericOptions);
<?php } ?>

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
    url  : "<?php echo base_url('invoice/selesai/')?>",
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

$("#form-invoice").submit(function(e) {
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


$('#tabel').on('click','.item_hapus',function(){
      $('.item_hapus').prop("disabled",true);
            var id=$(this).attr('data');
            $.ajax({
            type : "POST",
            url  : "<?php echo base_url('invoice/hapus_barang/')?>",
            data : {id_hapus: id},
                    success: function(data){
                            $('.item_hapus').prop("disabled",false);
                            location.reload();
                    }
                });
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
                        <h4 class="modal-title" id="myModalLabel">Konfirmasi Invoice</h4>
                    </div>
                    <form class="form-horizontal" action="<?php echo base_url('invoice/selesai');?>" method="post" id="form-selesai">
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
                    <form class="form-horizontal" action="<?php echo base_url('invoice/hapus');?>" method="post">
                    <div class="modal-body">
                                           
                            <input type="hidden" name="id_hapus" id="id_hapus" value="">
                            <div class="alert alert-default"><p>Apakah Anda yakin mau menghapus Invoice ini ?</p></div>
                                         
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
            <form  action="<?php echo base_url('invoice/update');?>" method="post" id="form-update-bobot">
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
        Buat Nota Invoice
      </h1>
      <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-refresh"></i> Transaksi</a></li>
        <li class="active">Invoice</li>
      </ol>
    </section>

    <section class="content">
    
      <div class="box box-warning">
        <div class="box-header with-border">
          <h3 class="box-title">Data Invoice</h3>
        </div>
        <!-- /.box-header -->
    
        <div class="box-body">
            <?php
            if($invoice->num_rows() > 0){
            foreach($invoice->result() as $r){
            ?>
            <form role="form" action="<?php echo base_url('invoice/simpan'); ?>" method="post" autocomplete="off" enctype="multipart/form-data" id="form-invoice">
            <input type="hidden" name="id" value="<?php echo $r->id_invoice;?>">
            <div class="table-responsive">
            <table class="table">
              <tr>
                <th style="width:40%">Tanggal :</th>
                <td><?php echo tgl_pecah($r->tanggal);?></td>

                <th>Pelanggan :</th>
                <td><?php echo $r->nama_pelanggan;?></td>
              </tr>
              <tr>
                <th style="width:40%">No Truk :</th>
                <td><?php echo $r->no_polisi;?></td>

                <th>Status :</th>
                <td><span class="badge <?php if($r->status == "Selesai") {echo 'btn-primary' ;} else {echo 'btn-warning' ;} ?>"><?php echo $r->status;?></span></td>
              </tr>
            </table>

        <div id="tagNama" class="form-group">
                  <label class="control-label" for="inputError"><i id="iconNama"></i> Harga Satuan KBK Angkut</label>
                  <input type="text" name="harga_kbk" id="harga_kbk" class="form-control" required placeholder="Total Tonase Sebelumnya" 
                  value="<?php echo (int) $r->harga_kbk;?>" onKeyup="hitungTotal()">
                  <span class="help-block" id="pesanNama"></span>
        </div>

        <div id="tagNama" class="form-group">
                  <label class="control-label" for="inputError"><i id="iconNama"></i> Grand Total</label>
                  <input type="text" name="tonase_0" id="tonase_0" class="form-control" readonly placeholder="Total Tonase Sebelumnya" value="<?php echo uang((int) $r->total);?>" >
                  <span class="help-block" id="pesanNama"></span>
        </div>

        

        </div>
            
          
          
          <button type="submit" class="btn btn-app">
          <i class="fa fa-save"></i> Simpan
          </button>

          <a href="javascript:;" id="hapusButton" class="btn btn-app" data="<?php echo $this->uri->segment(4);?>">
          <i class="fa fa-trash"></i> Hapus
          </a>
          
          <?php if($r->status == "Proses") {

          ?>
          <a href="javascript:;" id="selesaiButton" class="btn btn-app" data="<?php echo $this->uri->segment(4);?>" 
          >
          <i class="fa fa-check"></i> Selesai
          </a>
          <?php } else if($r->status == "Selesai") { ?>
          <a href="<?php echo base_url('transaksi/invoice/surat-jalan/'.$r->id_invoice);?>" class="btn btn-app" target="_blank">
          <i class="fa fa-truck"></i> Surat Jalan
          </a>
          <a href="<?php echo base_url('transaksi/invoice/cetak-nota/'.$r->id_invoice);?>" class="btn btn-app" target="_blank">
          <i class="fa fa-print"></i> Cetak
          </a>
          <?php } ?>
          <a href="<?php echo base_url('transaksi/invoice');?>" class="btn btn-app">
          <i class="fa fa-arrow-left"></i> Kembali
          </a>
          </form>


          <?php
          }
          
          ?>
          
          <?php if($r->status == "Proses") {
          
          ?>
        
        <form role="form" action="<?php echo base_url('invoice/tambah'); ?>" method="post" autocomplete="off" id="form-tambah">
        <input type="hidden" name="id_invoice" value="<?php echo $r->id_invoice;?>">

        <div id="tagNama" class="form-group">
                  <label class="control-label" for="inputError"><i id="iconNama"></i> Barang</label>
                  <input type="text" name="barang" id="barang" class="form-control" required="true" placeholder="Barang">
                  <span class="help-block" id="pesanNama"></span>
        </div>

        <div class="row">
        <div class="col-md-6">

        <div id="tagNama" class="form-group">
                  <label class="control-label" for="inputError"><i id="iconNama"></i> Qty</label>
                  <input type="number" name="qty" id="qty" class="form-control" required="true" placeholder="qty" min="1" max="9999999" value="1" onKeyup="hitung()">
                  <span class="help-block" id="pesanNama"></span>
        </div>
        </div>

        <div class="col-md-6">
        <div id="tagNama" class="form-group">
                  <label class="control-label" for="inputError"><i id="iconNama"></i> Harga Satuan</label>
                  <input type="text" name="harga" id="harga" class="form-control" required="true" placeholder="Harga" onKeyup="hitung()">
                  <span class="help-block" id="pesanNama"></span>
        </div>
        </div>
        </div>

        <div id="tagNama" class="form-group">
                  <label class="control-label" for="inputError"><i id="iconNama"></i> Sub Total</label>
                  <input type="text" name="sub_total" id="sub_total" class="form-control" readonly placeholder="Sub Total">
                  <span class="help-block" id="pesanNama"></span>
        </div>
        
        <button class="btn btn-app" type="submit" id="btn_bobot">
          <i class="fa fa-plus"></i> Tambah Barang
        </button>
        
        </form>

        <?php
          }
        
        $jumlah_data = $barang->num_rows();
        $jumlah_baris = ceil($jumlah_data / 10);
         if($jumlah_data > 0){ ?>
  <div class="table-responsive">
  <table class="table table-bordered table-striped" id="tabel">
  <thead>
    <tr>
          <th>No</th>
          <th>Barang</th>
          <th>Harga Satuan</th>
          <th>Qty</th>
          <th>Sub Total</th>
          <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
<?php $no = 0; foreach($barang->result() as $n){ $no++; ?>
    <tr>
      <td><?php echo $no;?></td>
      <td><?php echo $n->nama_barang;?></td>
      <td><?php echo uang($n->harga);?></td>
      <td><?php echo $q[] = $n->qty;?></td>
      <td><?php echo uang($b[] = $n->sub_total);?></td>
      
      <td>
      <?php if($r->status == "Proses") {
          
          ?>
          <a href="javascript:;" class="btn btn-sm btn-danger item_hapus" data="<?php echo $n->id_id;?>">
            <i class="fa fa-trash"></i> Hapus</a><?php } ?>
        </td>
    </tr>
<?php } ?>
    <tr>
      <td colspan="4"><strong>Grand Total</strong></td>
      <td><strong><?php echo uang(array_sum($b));?></strong></td>
      <td></td>
    </tr>
    <tr>
      <td colspan="4"><strong>Biaya Kuli Angkut <?php echo "(".uang(array_sum($q))." x ".(int)$r->harga_kbk.")";?></strong></td>
      <td><strong><?php echo uang(array_sum($q) * $r->harga_kbk);?></strong></td>
      <td></td>
    </tr>
  </tbody>
  </table>
</div>

        <?php
}
?>
        </div>


<?php
            }
        ?>
        </div>
        <!-- /.box-body -->
        
        
      </div>
      <!-- /.row -->
    </section>