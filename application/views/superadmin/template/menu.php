
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="<?php echo base_url("assets/foto/developer.jpg");?>" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p>Admin</p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
      
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">MENU UTAMA</li>
        <!--Menu Utama-->

        <li <?php if($this->uri->segment(1) == "setting"){echo "class='active treeview'";} else{echo "class='treeview'";}?>>
          <a href="#">
            <i class="fa fa-cog"></i>
            <span>Setting</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li <?php if($this->uri->segment(1) == "setting"  && $this->uri->segment(2) == "langganan"){echo"class='active'";} ?>><a href="<?php echo base_url('setting/langganan');?>"><i class="fa <?php if($this->uri->segment(1) == "setting"  && $this->uri->segment(2) == "langganan"){echo "fa-circle";} else{echo "fa-circle-o";} ?>"></i> Langganan</a></li>
            <li <?php if($this->uri->segment(1) == "setting"  && $this->uri->segment(2) == "setting-backup"){echo"class='active'";} ?>><a href="<?php echo base_url('setting/setting-backup');?>"><i class="fa <?php if($this->uri->segment(1) == "setting"  && $this->uri->segment(2) == "setting-backup"){echo "fa-circle";} else{echo "fa-circle-o";} ?>"></i> Setting Backup</a></li>
            <li <?php if($this->uri->segment(1) == "setting"  && $this->uri->segment(2) == "setting-maintenance"){echo"class='active'";} ?>><a href="<?php echo base_url('setting/setting-maintenance');?>"><i class="fa <?php if($this->uri->segment(1) == "setting"  && $this->uri->segment(2) == "setting-maintenance"){echo "fa-circle";} else{echo "fa-circle-o";} ?>"></i> Mode Perawatan</a></li>
          </ul>
        </li>

                
        <li <?php if($this->uri->segment(1) == "akun"){echo "class='active treeview'";} else{echo "class='treeview'";}?>>
          <a href="#">
            <i class="fa fa-user"></i>
            <span>Akun</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li <?php if($this->uri->segment(1) == "akun" && $this->uri->segment(2) == "ganti-password"){echo"class='active'";} ?>><a href="<?php echo base_url('akun/ganti-password');?>"><i class="fa <?php if($this->uri->segment(1) == "akun"  && $this->uri->segment(2) == "ganti-password"){echo "fa-circle";} else{echo "fa-circle-o";} ?>"></i> Ganti Password</a></li>
            <li <?php if($this->uri->segment(1) == "akun" && $this->uri->segment(2) == "tentang-software"){echo"class='active'";} ?>><a href="<?php echo base_url('akun/tentang-software');?>"><i class="fa <?php if($this->uri->segment(1) == "akun"  && $this->uri->segment(2) == "tentang-software"){echo "fa-circle";} else{echo "fa-circle-o";} ?>"></i> Tentang Software</a></li>
            <li <?php if($this->uri->segment(1) == "akun" && $this->uri->segment(2) == "logout"){echo"class='active'";} ?>><a href="<?php echo base_url('akun/logout');?>"><i class="fa <?php if($this->uri->segment(1) == "akun"  && $this->uri->segment(2) == "logout"){echo "fa-circle";} else{echo "fa-circle-o";} ?>"></i> Logout</a></li>
          </ul>
        </li>
        <!--Menu Utama-->
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">