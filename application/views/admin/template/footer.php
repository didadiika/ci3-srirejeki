</div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 1.1.0
    </div>
    <strong>Copyright &copy; 2024 <a href="https://www.madanidev.com" target="_blank">Madani Developer</a>.</strong> All rights
    reserved.
  </footer>


</div>
<!-- ./wrapper -->

<!--JavaScript Global-->
<script src="<?php echo base_url(); ?>assets/bower_components/jquery-ui/jquery-ui.min.js"></script>
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<script src="<?php echo base_url(); ?>assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="<?php echo base_url(); ?>assets/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<script src="<?php echo base_url(); ?>assets/bower_components/fastclick/lib/fastclick.js"></script>
<script src="<?php echo base_url(); ?>assets/dist/js/adminlte.min.js"></script>


<!--JavaScript Halaman-->
<script src="<?php echo base_url(); ?>assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/bower_components/select2/dist/js/select2.full.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/bower_components/ckeditor/ckeditor.js"></script>
  <script src="<?php echo base_url(); ?>assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
  <script src="<?php echo base_url(); ?>assets/bower_components/moment/min/moment.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
  <script src="<?php echo base_url(); ?>assets/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/dika-custom/autoNumeric/autoNumeric.min.js"></script>
  <script>
    const autoNumericOptionsEuro = {
    allowDecimalPadding        : true, 
    currencySymbol             : '',
    currencySymbolPlacement    : 'p',
    decimalCharacter           : ',',
    digitGroupSeparator        : '.',
    emptyInputBehavior         : 'zero',
    minimumValue               : '0'
};

var jumlah = document.getElementById("jumlah");
if(jumlah){new AutoNumeric('#jumlah',autoNumericOptionsEuro);}
</script>
  <script>
  $(function () {
    $('.select2').select2();
    $('#example1').DataTable();
    $('#example2').DataTable({
      'paging'      : false,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false
    });

    $('#example3').DataTable({
      'paging'      : false
    });
    
  })
</script>

<script src="<?php echo base_url(); ?>assets/bower_components/select2/dist/js/select2.full.min.js"></script>
<script src="<?php echo base_url(); ?>assets/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script>
  $(function () {
  
  var batas = 10;
  $('#id_pengirim').select2({
           minimumInputLength: 2,
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

  $('#id_pelanggan').select2({
           minimumInputLength: 2,
           language: {
  	        inputTooShort: function() {
  		      return 'Ketik minimum 2 huruf';}
                    },
           allowClear: true,
           placeholder: 'Masukkan Nama Pelanggan' ,
           ajax: {
              dataType: 'json',
              url:'<?= base_url('invoice/pelanggan_cari') ?>',
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
              results: response.pelanggan,
              pagination:{ more:(params.page * batas) < response.count_filtered}
             };
            },
            cache: true
          }
  });

   
    $('#example1').DataTable();
    $('#example2').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false
    });
  //Function()
  })

</script>
<script>
  $(function () {

  	$('#tgl').datepicker({
      autoclose: true
    });
    $('#datepicker').datepicker({
      autoclose: true
    });
    
  });
</script>
</body>
</html>
