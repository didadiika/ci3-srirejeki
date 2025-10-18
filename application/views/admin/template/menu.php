
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="<?php echo base_url("assets/dist/img/kasir.jpg");?>" class="img-circle" alt="User Image">
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

        <li <?php if($this->uri->segment(1) == "data"){echo "class='active treeview'";} else{echo "class='treeview'";}?>>
          <a href="#">
            <i class="fa fa-navicon"></i>
            <span>Data</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li <?php if($this->uri->segment(1) == "data"  && $this->uri->segment(2) == "pengirim"){echo"class='active'";} ?>><a href="<?php echo base_url('data/pengirim');?>"><i class="fa <?php if($this->uri->segment(1) == "data"  && $this->uri->segment(2) == "pengirim"){echo "fa-circle";} else{echo "fa-circle-o";} ?>"></i> Data Pengirim</a></li>
            <li <?php if($this->uri->segment(1) == "data"  && $this->uri->segment(2) == "pelanggan"){echo"class='active'";} ?>><a href="<?php echo base_url('data/pelanggan');?>"><i class="fa <?php if($this->uri->segment(1) == "data"  && $this->uri->segment(2) == "pelanggan"){echo "fa-circle";} else{echo "fa-circle-o";} ?>"></i> Data Pelanggan</a></li>
            <li <?php if($this->uri->segment(1) == "data"  && $this->uri->segment(2) == "karyawan"){echo"class='active'";} ?>><a href="<?php echo base_url('data/karyawan');?>"><i class="fa <?php if($this->uri->segment(1) == "data"  && $this->uri->segment(2) == "karyawan"){echo "fa-circle";} else{echo "fa-circle-o";} ?>"></i> Data Karyawan</a></li>
          </ul>
        </li>

        <li <?php if($this->uri->segment(1) == "transaksi"){echo "class='active treeview'";} else{echo "class='treeview'";}?>>
          <a href="#">
            <i class="fa fa-refresh"></i>
            <span>Transaksi</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li <?php if($this->uri->segment(1) == "transaksi"  && $this->uri->segment(2) == "pembelian"){echo"class='active'";} ?>><a href="<?php echo base_url('transaksi/pembelian');?>"><i class="fa <?php if($this->uri->segment(1) == "transaksi"  && $this->uri->segment(2) == "pembelian"){echo "fa-circle";} else{echo "fa-circle-o";} ?>"></i> Pembelian</a></li>
            <li <?php if($this->uri->segment(1) == "transaksi"  && $this->uri->segment(2) == "invoice"){echo"class='active'";} ?>><a href="<?php echo base_url('transaksi/invoice');?>"><i class="fa <?php if($this->uri->segment(1) == "transaksi"  && $this->uri->segment(2) == "invoice"){echo "fa-circle";} else{echo "fa-circle-o";} ?>"></i> Invoice</a></li>
            <li <?php if($this->uri->segment(1) == "transaksi"  && $this->uri->segment(2) == "gaji-karyawan"){echo"class='active'";} ?>><a href="<?php echo base_url('transaksi/gaji-karyawan');?>"><i class="fa <?php if($this->uri->segment(1) == "transaksi"  && $this->uri->segment(2) == "gaji-karyawan"){echo "fa-circle";} else{echo "fa-circle-o";} ?>"></i> Gaji Karyawan</a></li>
            <li <?php if($this->uri->segment(1) == "transaksi"  && $this->uri->segment(2) == "buku-keuangan"){echo"class='active'";} ?>><a href="<?php echo base_url('transaksi/buku-keuangan');?>"><i class="fa <?php if($this->uri->segment(1) == "transaksi"  && $this->uri->segment(2) == "buku-keuangan"){echo "fa-circle";} else{echo "fa-circle-o";} ?>"></i> Buku Keuangan</a></li>
          </ul>
        </li>

        <li <?php if($this->uri->segment(1) == "laporan"){echo "class='active treeview'";} else{echo "class='treeview'";}?>>
          <a href="#">
            <i class="fa fa-file-text-o"></i>
            <span>Laporan</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li <?php if($this->uri->segment(1) == "laporan" && $this->uri->segment(2) == "laporan-pembelian"){echo"class='active'";} ?>><a href="<?php echo base_url('laporan/laporan-pembelian');?>"><i class="fa <?php if($this->uri->segment(1) == "laporan"  && $this->uri->segment(2) == "laporan-pembelian"){echo "fa-circle";} else{echo "fa-circle-o";} ?>"></i> Laporan Pembelian</a></li>
            <li <?php if($this->uri->segment(1) == "laporan" && $this->uri->segment(2) == "laporan-penjualan"){echo"class='active'";} ?>><a href="<?php echo base_url('laporan/laporan-penjualan');?>"><i class="fa <?php if($this->uri->segment(1) == "laporan"  && $this->uri->segment(2) == "laporan-penjualan"){echo "fa-circle";} else{echo "fa-circle-o";} ?>"></i> Laporan Penjualan</a></li>
            <li <?php if($this->uri->segment(1) == "laporan" && $this->uri->segment(2) == "laporan-piutang"){echo"class='active'";} ?>><a href="<?php echo base_url('laporan/laporan-piutang');?>"><i class="fa <?php if($this->uri->segment(1) == "laporan"  && $this->uri->segment(2) == "laporan-piutang"){echo "fa-circle";} else{echo "fa-circle-o";} ?>"></i> Laporan Piutang</a></li>
            <li <?php if($this->uri->segment(1) == "laporan" && $this->uri->segment(2) == "laporan-buku-keuangan"){echo"class='active'";} ?>><a href="<?php echo base_url('laporan/laporan-buku-keuangan');?>"><i class="fa <?php if($this->uri->segment(1) == "laporan"  && $this->uri->segment(2) == "laporan-buku-keuangan"){echo "fa-circle";} else{echo "fa-circle-o";} ?>"></i> Laporan Buku Keuangan</a></li>
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
            <li <?php if($this->uri->segment(1) == "akun" && $this->uri->segment(2) == "rekening"){echo"class='active'";} ?>><a href="<?php echo base_url('akun/rekening');?>"><i class="fa <?php if($this->uri->segment(1) == "akun"  && $this->uri->segment(2) == "rekening"){echo "fa-circle";} else{echo "fa-circle-o";} ?>"></i> Rekening Bank</a></li>
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