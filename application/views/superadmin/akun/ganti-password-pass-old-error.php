<section class="content-header">
      <h1>
        Ganti Password
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-user"></i> Akun</a></li>
        <li class="active">Ganti Password</li>
      </ol>
    </section>

    <section class="content">
      <div class="box box-warning">
        <div class="box-header with-border">
          <h3 class="box-title">Masukkan Data</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
        <div class='callout callout-danger'>
        Password lama anda salah!!!
      </div>
        <?php
        foreach($user->result() as $s){
        ?>
          <form role="form" action="<?php echo base_url('akun/ganti_password_update/'); ?>" method="post" autocomplete="off">
            <!-- text input -->
            <div id="tagNama" class="form-group">
                  <label class="control-label" for="inputError"><i id="iconNama"></i> Password Sekarang</label>
                  <input type="password" name="pass1" id="pass1" class="form-control" maxlength="32" placeholder="Password Sekarang"  required >
                  <span class="help-block" id="pesanNama"></span>
            </div>

            <div id="tagNama" class="form-group">
                  <label class="control-label" for="inputError"><i id="iconNama"></i> Password Baru</label>
                  <input type="password" name="pass2" id="pass2" class="form-control" placeholder="Password Baru"  required  maxlength="32" >
                  <span class="help-block" id="pesanNama"></span>
            </div>

            <div id="tagNama" class="form-group">
                  <label class="control-label" for="inputError"><i id="iconNama"></i> Masukkan Lagi Password Baru</label>
                  <input type="password" name="pass3" id="pass3" class="form-control" placeholder="Password Baru"  required  maxlength="32" >
                  <span class="help-block" id="pesanNama"></span>
            </div>
            
          
          <button class="btn btn-app" type="submit" id="submit">
          <i class="fa fa-save"></i> Simpan
          </button>
         
          <button class="btn btn-app" type="button" onClick="self.history.back()">
          <i class="fa fa-arrow-left"></i> Kembali
          </button>
            
          </form>
          <?php 
            }
          ?>
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.row -->
    </section>