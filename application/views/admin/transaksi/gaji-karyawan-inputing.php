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

  function hitung(g,q,p,s){
		
        var gaji = $("#"+g).val().toString().replace(/\./g,"");
        var qty = $("#"+q).val();
        var potongan = $("#"+p).val().toString().replace(/\./g,"");
        var subtotal = (parseInt(gaji) * parseInt(qty)) - parseInt(potongan);
    
        $("#"+s).val(uang(subtotal));
    }

 function grand_Total(j) {
    
  let grandTotal = 0
  for (i=1; i<=j; i++) {
    grandTotal += parseInt(document.getElementById("sub_total" + i).value.toString().replace(/\./g,""))
  }
  $("#grand_total").html(uang(grandTotal));
  
}
  
$(document).ready(function(){    
let grandTotal = 0
for (i=1; i<=<?php echo $kar->num_rows(); ?>; i++) {
    grandTotal += parseInt(document.getElementById("sub_total" + i).value.toString().replace(/\./g,""))
}
$("#grand_total").html(uang(grandTotal));
    
  

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
    url  : "<?php echo base_url('gaji/selesai/')?>",
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
$("#form-tambah")[0].reset();
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




</script>
<!--MODAL SELESAI-->
<div class="modal modal-primary fade" id="ModalSelesai" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">X</span></button>
                        <h4 class="modal-title" id="myModalLabel">Konfirmasi Gaji Karyawan</h4>
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
                    <form class="form-horizontal" action="<?php echo base_url('gaji/hapus');?>" method="post">
                    <div class="modal-body">
                                           
                            <input type="hidden" name="id_hapus" id="id_hapus" value="">
                            <div class="alert alert-default"><p>Apakah Anda yakin mau menghapus Gaji Karyawan ini ?</p></div>
                                         
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
        Buat Nota Gaji Karyawan
      </h1>
      <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-refresh"></i> Transaksi</a></li>
        <li class="active">Gaji Karyawan</li>
      </ol>
    </section>

    <section class="content">
    
      <div class="box box-warning">
        <div class="box-header with-border">
          <h3 class="box-title">Data Gaji Karyawan</h3>
        </div>
        <!-- /.box-header -->
    
        <div class="box-body">
            <?php
            if($gaji->num_rows() > 0){
            foreach($gaji->result() as $r){
            ?>
            <form role="form" action="<?php echo base_url('invoice/simpan'); ?>" method="post" autocomplete="off" enctype="multipart/form-data" id="form-invoice">
            <input type="hidden" name="id" value="<?php echo $r->id_ka;?>">
            <div class="table-responsive">
            <table class="table">
              <tr>
                <th style="width:40%">Tanggal :</th>
                <td><?php echo tgl_pecah($r->tanggal);?></td>
              </tr>
              <tr>

                <th>Status :</th>
                <td><span class="badge <?php if($r->status == "Selesai") {echo 'btn-primary' ;} else {echo 'btn-warning' ;} ?>"><?php echo $r->status;?></span></td>
              </tr>
            </table>

        <div id="tagNama" class="form-group">
                  <label class="control-label" for="inputError"><i id="iconNama"></i> Grand Total</label>
                  <input type="text" name="tonase_0" id="tonase_0" class="form-control" readonly placeholder="Total Tonase Sebelumnya" value="<?php echo uang((int) $r->total);?>" >
                  <span class="help-block" id="pesanNama"></span>
        </div>

        

        </div>
            
          
          
         
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
          <a href="<?php echo base_url('transaksi/gaji-karyawan/cetak/'.$r->id_ka);?>" class="btn btn-app" target="_blank">
          <i class="fa fa-print"></i> Cetak
          </a>
          <?php } ?>
          <a href="<?php echo base_url('transaksi/gaji-karyawan');?>" class="btn btn-app">
          <i class="fa fa-arrow-left"></i> Kembali
          </a>
          </form>


          <?php
          }
          
          ?>
          
          <?php if($r->status == "Proses") {
          
          ?>
        
        <form role="form" action="<?php echo base_url('gaji/tambah'); ?>" method="post" autocomplete="off" id="form-tambah">
        <input type="hidden" name="id_ka" value="<?php echo $r->id_ka;?>">

        <?php
    if($kar->num_rows() > 0){
        ?>
        <div class="table-responsive">
        <table  class="table table-bordered table-striped">
        <thead>
        <tr>
        <th>No</th>
        <th>Nama Karyawan</th>
        <th>Gaji Satuan</th>
        <th>Jumlah Hari</th>
        <th>Potongan</th>
        <th>Sub Total</th>
        </tr>
        </thead>
        <?php
        $no = 0;
        $gaji = array();
        $qty = array();
        $potongan = array();
        $sub_total = array();
        foreach($kar->result() as $k){
            #Periksa apakah sudah tersimpan, jika sudah tampilkan#
            $gs = $this->db->query("select * from karyawan_abs_d where id_ka='$r->id_ka' and id_karyawan='$k->id_karyawan' ");
            if($gs->num_rows() > 0){
                foreach($gs->result() as $d){
                    $qty = $d->qty;
                    $gaji = $d->gaji;
                    $potongan = $d->potongan;
                    $sub_total = $d->sub_total;
                }
            } else {
                    $qty = 1;
                    $gaji = $setting_gaji;
                    $potongan = 0;
                    $sub_total = $setting_gaji;
            }
        $no++;
        ?>
        <input type="hidden" name="items[<?php echo $no;?>][id]" value="<?php echo $k->id_karyawan;?>">
        <tr>
        <td><?php echo $no;?></td>
        <td><?php echo $k->nama_karyawan;?></td>
        <td><input type="text" name="items[<?php echo $no;?>][gaji]" id="gaji<?php echo $no;?>" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this),hitung('gaji<?php echo $no;?>','qty<?php echo $no;?>','potongan<?php echo $no;?>','sub_total<?php echo $no;?>'),grand_Total(<?php echo $kar->num_rows();?>);" class="form-control input-sm" required value="<?php echo uang($gaji);?>"></td>
        <td><input type="number" name="items[<?php echo $no;?>][qty]" id="qty<?php echo $no;?>" class="form-control input-sm" min="0" required onchange="hitung('gaji<?php echo $no;?>','qty<?php echo $no;?>','potongan<?php echo $no;?>','sub_total<?php echo $no;?>'),grand_Total(<?php echo $kar->num_rows();?>)" value="<?php echo $qty;?>" onkeyup="hitung('gaji<?php echo $no;?>','qty<?php echo $no;?>','potongan<?php echo $no;?>','sub_total<?php echo $no;?>'),grand_Total(<?php echo $kar->num_rows();?>)"></td>
        <td><input type="text" name="items[<?php echo $no;?>][potongan]" id="potongan<?php echo $no;?>" class="form-control input-sm" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this),hitung('gaji<?php echo $no;?>','qty<?php echo $no;?>','potongan<?php echo $no;?>','sub_total<?php echo $no;?>'),grand_Total(<?php echo $kar->num_rows();?>);" required value="<?php echo uang($potongan);?>" ></td>
        <td><input type="text" name="items[<?php echo $no;?>][sub_total]" id="sub_total<?php echo $no;?>" class="form-control input-sm" readonly value="<?php echo uang($sub_total);?>"></td>
        </tr>
           <?php
        }
        ?>
        <input type="hidden" name="jml" value="<?php echo $no;?>">
        <tr>
        <td colspan="5"><strong>Total</strong></td>
        <td><strong><span id="grand_total"></span></strong></td>
        </tr>
        </table>
      </div>
        <?php
        }
        ?>
        
        <button class="btn btn-app" type="submit" id="btn_simpan">
          <i class="fa fa-save"></i> Simpan Gaji
        </button>
        
        </form>

        <?php
          } else {


    if($kar->num_rows() > 0){
        ?>
        <div class="table-responsive">
        <table  class="table table-bordered table-striped">
        <thead>
        <tr>
        <th>No</th>
        <th>Nama Karyawan</th>
        <th>Gaji Satuan</th>
        <th>Jumlah Hari</th>
        <th>Potongan</th>
        <th>Sub Total</th>
        </tr>
        </thead>
        <?php
        $no = 0;
        $gaji = array();
        $qty = array();
        $potongan = array();
        $sub_total = array();
        foreach($kar->result() as $k){
            #Periksa apakah sudah tersimpan, jika sudah tampilkan#
            $gs = $this->db->query("select * from karyawan_abs_d where id_ka='$r->id_ka' and id_karyawan='$k->id_karyawan' ");
            if($gs->num_rows() > 0){
                foreach($gs->result() as $d){
                    $qty = $d->qty;
                    $gaji = $d->gaji;
                    $potongan = $d->potongan;
                    $sub_total = $d->sub_total;
                }
            } else {
                    $qty = 1;
                    $gaji = $setting_gaji;
                    $potongan = 0;
                    $sub_total = $setting_gaji;
            }
        $no++;
        ?>
        
        <tr>
        <td><?php echo $no;?></td>
        <td><?php echo $k->nama_karyawan;?></td>
        <td><input type="text"  id="gaji<?php echo $no;?>" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this),hitung('gaji<?php echo $no;?>','qty<?php echo $no;?>','potongan<?php echo $no;?>','sub_total<?php echo $no;?>'),grand_Total(<?php echo $kar->num_rows();?>);" class="form-control input-sm" readonly value="<?php echo uang($gaji);?>"></td>
        <td><input type="number"  id="qty<?php echo $no;?>" class="form-control input-sm" min="0" readonly onchange="hitung('gaji<?php echo $no;?>','qty<?php echo $no;?>','potongan<?php echo $no;?>','sub_total<?php echo $no;?>'),grand_Total(<?php echo $kar->num_rows();?>)" value="<?php echo $qty;?>" onkeyup="hitung('gaji<?php echo $no;?>','qty<?php echo $no;?>','potongan<?php echo $no;?>','sub_total<?php echo $no;?>'),grand_Total(<?php echo $kar->num_rows();?>)"></td>
        <td><input type="text"  id="potongan<?php echo $no;?>" class="form-control input-sm" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this),hitung('gaji<?php echo $no;?>','qty<?php echo $no;?>','potongan<?php echo $no;?>','sub_total<?php echo $no;?>'),grand_Total(<?php echo $kar->num_rows();?>);" readonly value="<?php echo uang($potongan);?>" ></td>
        <td><input type="text"  id="sub_total<?php echo $no;?>" class="form-control input-sm" readonly value="<?php echo uang($sub_total);?>"></td>
        </tr>
           <?php
        }
        ?>
        <input type="hidden" name="jml" value="<?php echo $no;?>">
        <tr>
        <td colspan="5"><strong>Total</strong></td>
        <td><strong><span id="grand_total"></span></strong></td>
        </tr>
        </table>
      </div>
        <?php
        }
        }
        
        }
        ?>
        </div>
        <!-- /.box-body -->
        
        
      </div>
      <!-- /.row -->
    </section>