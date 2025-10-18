<section class="content-header">
      <h1>
        Data User
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-user"></i> Akun</a></li>
        <li class="active">Data User</li>
      </ol>
    </section>



    <section class="content">

<a class="btn btn-app" href="<?php echo base_url('akun/data_user_input');?>">
<i class="fa fa-plus"></i> Tambah Data User
</a>



<div class="box">
  <!--Tombol Tambah Data-->
<?php
if($user->num_rows() > 0){
?>
            <div class="box-header">
              <h3 class="box-title">Daftar</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="table-responsive">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>No</th>
                  <th>Username</th>
                  <th>Password</th>
                  <th>Level</th>
                  <th>Status Akun</th>
                  <th>Aksi</th>
                </tr>
                </thead>
                <tbody>
                
                  <?php
                  
                  $no = "";
                  foreach($user->result() as $s){
                    $no++;
                    
                  ?>
                <tr>
                <td><?php echo $no;?></td>
                <td><?php echo $s->username;?></td>
                <td><?php echo $s->password;?></td>
                
                  <td><?php echo $s->level;?></td>
                  <td><?php echo $s->status_akun;?></td>
                  
                <td>
                <?php
                if($s->level == "Admin" && ($this->session->level == "Owner" || $this->session->level == "Programmer") )
                {
                ?>
                <a class="btn btn-default" href="<?php echo base_url('akun/data_user_edit/'.$this->enkripsi_url->encode($s->id_user));?>" title="Edit">
                <i class="fa fa-edit"></i>
                </a>
                <a class="btn btn-default" href="#" data-toggle="modal" 
                data-target="#modal-danger<?php echo $s->id_user;?>" title="Hapus">
                <i class="fa fa-trash-o"></i>
                </a>
                <div class="modal modal-danger fade" id="modal-danger<?php echo $s->id_user;?>">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Konfirmasi</h4>
              </div>
              <div class="modal-body">
                <p>Hapus User : <?php echo $s->username;?>?</p>
              </div>
              <div class="modal-footer">
                <a class="btn btn-outline pull-left" data-dismiss="modal">Tidak</a>
                <a class="btn btn-outline" href="<?php echo base_url('akun/data_user_hapus/'.$this->enkripsi_url->encode($s->id_user));?>">Ya</a>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
        <?php
                  }
                  ?>
              </td>
                </tr>    
                  <?php
                  }
                  ?>
                </tbody>
              </table>
            </div>
            </div>
<?php
}
else
{
  echo"
  <div class='callout callout-danger'>
        Data tidak ditemukan.
      </div>
  ";
}
?>
            <!-- /.box-body -->
          </div>
          </section>