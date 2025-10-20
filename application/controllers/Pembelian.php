<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once (dirname(__FILE__) . "/BaseController.php");

class Pembelian extends BaseController{
    
	function __construct(){
		parent::__construct();
		$cek_login = $this->session->userdata('authenticated');
        if ($cek_login != true) {
			redirect( base_url('login') );
        }


	}

	
	function index(){
        
        
        
        #Mengambil data toko di tabel suplier dan memasukkan ke variabel data#

		#Menampilkan halaman suplier dan mem passing variabel data#
        $level = $this->session->level;
        if($level == "Programmer")
        {
            $this->load->view("programmer/template/header.php");
            $this->load->view("programmer/template/menu.php");

        } else if($level == "Owner"){
            $this->load->view("owner/template/header.php");
            $this->load->view("owner/template/menu.php");
        } else if($level == "Admin"){
            $this->load->view("admin/template/header.php");
            $this->load->view("admin/template/menu.php");
        }
        $this->load->view("admin/transaksi/pembelian.php");
        $this->load->view("admin/template/footer.php");
        #Menampilkan halaman suplier dan mem passing variabel data#
    }
    
    function tampil(){
		#Mengambil data kain secara serverside#
		$this->load->model("pembelian_model");
		$start = isset($_GET["start"]) ? $_GET["start"] : 0;
		$no = $start + 1;
		$d = $this->pembelian_model->make_datatables();
		$data = array();
		foreach($d as $r){
		    /* Ambil akumulasi pembayaran */
            $bayar = 0;
            $by = $this->db->query("select sum(bayar) as bayar from pembelian_bayar where id_pembelian='$r->id_pembelian' and deleted_at is null");
            if($by->num_rows() > 0){
                foreach($by->result() as $b){
                    $bayar = $b->bayar;
                }
            }
            /* -------------------------------------------------- */

			$sub_array = array();
			$sub_array[] = $no++;
            $sub_array[] = tgl_pecah($r->tanggal);
            $sub_array[] = $r->no_polisi;
            $sub_array[] = $r->nama_pengirim;
            $sub_array[] = (int)$r->total_tonase;
            $sub_array[] = uang($r->harga_satuan);
            $sub_array[] = uang($r->harga_satuan * $r->total_tonase);
            
            if($r->status == "Proses")
            {
                $sub_array[] = '<span class="badge btn-warning">Proses</span>';
                if($r->status_bayar == "Belum Lunas"){ $sub_array[] = '<span class="badge btn-warning">Belum Lunas</span>'; } else {
                    $sub_array[] = '<span class="badge btn-success">Lunas</span>';
                }
                $sub_array[] = uang($r->harga_satuan * $r->total_tonase - $bayar);
                if($r->harga_satuan > 0 && $r->harga_kbk > 0){
                    $sub_array[] = '
                    <div class="btn-group">
                    <button type="button" class="btn btn-default">Pilih</button>
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                        <span class="caret"></span>
                        <span class="sr-only">Toggle Dropdown</span>
                    </button>
                        <ul class="dropdown-menu" role="menu">
                        <li><a href="'.base_url('transaksi/pembelian/tambah-timbangan/'.$r->id_pembelian).'">Tambah Timbangan</a></li>
                        <li><a href="javascript:;" class="item_selesai" data-selesai="'.$r->id_pembelian.'">Selesai</a></li>
                        <li><a href="javascript:;" class="item_hapus" data="'.$r->id_pembelian.'" >Hapus</a></li>
                        </ul>
                    </div>';
                } else {
                    $sub_array[] = '
                    <div class="btn-group">
                    <button type="button" class="btn btn-default">Pilih</button>
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                        <span class="caret"></span>
                        <span class="sr-only">Toggle Dropdown</span>
                    </button>
                        <ul class="dropdown-menu" role="menu">
                        <li><a href="'.base_url('transaksi/pembelian/tambah-timbangan/'.$r->id_pembelian).'">Tambah Timbangan</a></li>
                        <li><a href="javascript:;" class="item_hapus" data="'.$r->id_pembelian.'" >Hapus</a></li>
                        </ul>
                    </div>';
                }
                
            }
            else
            {
                if($r->status_bayar == "Belum Lunas"){ 
                    $sub_array[] = '<span class="badge btn-primary">Selesai</span>';
                    $sub_array[] = '<span class="badge btn-warning">Belum Lunas</span>'; 
                    $sub_array[] = uang($r->harga_satuan * $r->total_tonase - $bayar);
                    $sub_array[] = '
                    <div class="btn-group">
                    <button type="button" class="btn btn-default">Pilih</button>
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                        <span class="caret"></span>
                        <span class="sr-only">Toggle Dropdown</span>
                    </button>
                        <ul class="dropdown-menu" role="menu">
                        <li><a href="'.base_url('transaksi/pembelian/tambah-timbangan/'.$r->id_pembelian).'">Lihat</a></li>
                        <li><a href="javascript:void(0)" data="'.$r->id_pembelian.'" class="item_bayar"
                        tanggal-pembelian="'.tgl_pecah($r->tanggal).'" pengirim="'.$r->nama_pengirim.'" 
                        total-pembelian="'.(int)$r->harga_satuan * $r->total_tonase.'" dibayar="'.(int)$bayar.'" sisa="'.(int)($r->harga_satuan * $r->total_tonase - $bayar).'"
                        >Input Pembayaran</a></li>
                        <li><a href="javascript:void(0)" data="'.$r->id_pembelian.'" class="item_riwayat">Riwayat Pembayaran</a></li>
                        <li><a href="'.base_url('transaksi/pembelian/cetak-nota/'.$r->id_pembelian).'" target="_blank">Cetak</a></li>
                        <li><a href="javascript:;" class="item_hapus" data="'.$r->id_pembelian.'" >Hapus</a></li>
                        </ul>
                    </div>';
                } else {
                    $sub_array[] = '<span class="badge btn-primary">Selesai</span>';
                    $sub_array[] = '<span class="badge btn-success">Lunas</span>'; 
                    $sub_array[] = uang($r->harga_satuan * $r->total_tonase - $bayar);
                    $sub_array[] = '
                    <div class="btn-group">
                    <button type="button" class="btn btn-default">Pilih</button>
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                        <span class="caret"></span>
                        <span class="sr-only">Toggle Dropdown</span>
                    </button>
                        <ul class="dropdown-menu" role="menu">
                        <li><a href="'.base_url('transaksi/pembelian/tambah-timbangan/'.$r->id_pembelian).'">Lihat</a></li>
                        <li><a href="javascript:void(0)" data="'.$r->id_pembelian.'" class="item_riwayat">Riwayat Pembayaran</a></li>
                        <li><a href="'.base_url('transaksi/pembelian/cetak-nota/'.$r->id_pembelian).'" target="_blank">Cetak</a></li>
                        <li><a href="javascript:;" class="item_hapus" data="'.$r->id_pembelian.'" >Hapus</a></li>
                        </ul>
                    </div>';

                }
            }
			
			$data[] = $sub_array;
		}
		#Mengambil data kategori di tabel kategori dan memasukkan ke variabel data#
		$draw = "";
		if(isset($_POST["draw"])){$draw = $_POST["draw"];}

			$output = array(
				"draw" 				=> intval($draw),
				"recordsTotal" 		=> $this->pembelian_model->get_all_data(),
				"recordsFiltered" 	=> $this->pembelian_model->get_filtered_data(),
				"data"				=> $data
		
		);

		echo json_encode($output);
	}

    function tambah_timbangan($id){
        

        #Menampilkan halaman suplier dan mem passing variabel data#
        $level = $this->session->level;
        if($level == "Programmer")
        {
            $this->load->view("programmer/template/header.php");
            $this->load->view("programmer/template/menu.php");

        } else if($level == "Owner"){
            $this->load->view("owner/template/header.php");
            $this->load->view("owner/template/menu.php");
        } else if($level == "Admin"){
            $this->load->view("admin/template/header.php");
            $this->load->view("admin/template/menu.php");
        }

        
        if($id)
        {
        #$id = $this->uri->segment(4);
        $this->load->model("pembelian_model");
        $data["pembelian"] = $this->db->query("select * from pembelian, pengirim where pembelian.id_pembelian='$id' and pembelian.id_pengirim = pengirim.id_pengirim");
        $data["timbangan"] = $this->db->query("select * from pembelian_timbangan where pembelian_timbangan.id_pembelian='$id' order by pembelian_timbangan.created_at asc");
        $this->load->view("admin/transaksi/pembelian-inputing.php",$data);
        }
        $this->load->view("admin/template/footer.php");
        #Menampilkan halaman suplier dan mem passing variabel data#
    }


    function buat(){
        $id_pengirim = $this->input->post("id_pengirim");
        $tanggal = tgl_pecah($this->input->post("tanggal"));
        $no_polisi = $this->input->post("no_polisi");  
        $jenis_timbangan = $this->input->post("jenis_timbangan");  
        $jenis_timbangan = ($jenis_timbangan == "Ya") ? 1 : 0;
        $harga_kbk = 0;
        $harga_timbangan = 0;
        $kbk = $this->db->query("select * from sistem_setting ");
        if($kbk->num_rows() > 0){
            foreach($kbk->result() as $r){
                $harga_kbk = $r->kuli_bongkar_price;
                if($jenis_timbangan == 0){
                    $harga_timbangan = $r->timbangan_price;
                }
            }
        }

        
        
        $id = id_primary();
        $data = array("id_pembelian"=>$id,"id_pengirim"=>$id_pengirim,"tanggal"=>$tanggal,
        "no_polisi"=>$no_polisi,"harga_kbk"=>$harga_kbk,"timbangan"=>$harga_timbangan,"status"=>"Proses");
        $this->load->model("pembelian_model");
        $this->pembelian_model->simpan_data("pembelian",$data);
        
        #redirect(base_url('data/pengirim')); 
        
    }


    function cetak_nota($id){

        $data["pembelian"] = $this->db->query("select * from pembelian, pengirim where pembelian.id_pengirim = pengirim.id_pengirim and 
        pembelian.id_pembelian='$id' and
        pembelian.status='Selesai' and 
        pembelian.deleted_at is null ");
        $data["timbangan"] = $this->db->query("select * from pembelian_timbangan where id_pembelian='$id' order by created_at asc");
		

		#Menampilkan halaman#
		$this->load->view("admin/transaksi/pembelian-cetak-tampil.php",$data);
        #Menampilkan halaman#

    }

    function pengirim_cari(){
        $cari = trim($this->input->get("cari"));
        $page = $this->input->get("page");#Untuk Pagination
        $batas = $this->input->get("batas");
        $offset = ($page - 1) * $batas;
        if(!empty($cari))
        {   
            $query = $this->db->query("select * from pengirim where 
            deleted_at is null and
            nama_pengirim like '%$cari%' order by nama_pengirim asc
            limit $offset,$batas
            ");

            $count_filtered = $query->num_rows();
            $count_all = $this->db->query("select * from pengirim where 
            deleted_at is null and
            nama_pengirim like '%$cari%' order by nama_pengirim asc
            ")->num_rows();
            
            if($query->num_rows() > 0){
            $list = array();
            foreach($query->result() as $row) {
                    $list[] = array("id"=>$row->id_pengirim, "text"=>$row->nama_pengirim);
                
            }
            $hasil = array("pengirim" => $list,
            "count_filtered" => $count_all
            );
            echo json_encode($hasil);
            }
            else
            {
                echo "Tidak Ketemu";
            }
            
        }
        else
        {
            echo "Tidak Valid";
        }
    }

    function hapus(){
        $id = $this->input->post('id_hapus');

        #Hapus tabel transaksi dan transaksi_pembelian
        $p = $this->db->query("select * from transaksi_pembelian where id_pembelian='$id' ");
        if($p->num_rows() > 0){
            foreach($p->result() as $r){
                $this->db->query("update transaksi set deleted_at='".date("Y-m-d H:i:s")."' where id_transaksi='$r->id_transaksi' ");
            }
            $this->db->query("update transaksi_pembelian set deleted_at='".date("Y-m-d H:i:s")."' where id_pembelian='$id' ");
        }
        #Hapus Tabel pembelian
        
        $data = array("deleted_at"=>date("Y-m-d H:i:s"));
        $where = array("id_pembelian"=>$id);

        $this->load->model("pembelian_model");
        $this->pembelian_model->update_data("pembelian",$data,$where);
        

        redirect(base_url('transaksi/pembelian')); 
        
    }

    function hapus_bobot(){
        $id = $this->input->post('id_hapus');
        
        
        $p = $this->db->query("select * from pembelian_timbangan where id_pt='$id' ");
        if($p->num_rows() > 0){

            foreach($p->result() as $r){
                $id_pembelian = $r->id_pembelian;
            }
            
            $this->db->query("delete from pembelian_timbangan where id_pt='$id' ");

            $tot = $this->db->query("select sum(bobot) as b from pembelian_timbangan where id_pembelian='$id_pembelian' ");
            $total = 0;
            if($tot->num_rows() > 0){
                foreach($tot->result() as $t){
                    $total = $t->b;
                }
                $this->db->query("update pembelian set total_tonase = '$total' where id_pembelian='$id_pembelian' ");
            }

        }
        

        redirect(base_url('transaksi/pembelian')); 
        
    }

    function update(){
        $id = $this->input->post('id_edit');
        $bobot = $this->input->post('bobot_update');
        $this->db->query("update pembelian_timbangan set bobot='$bobot' where id_pt='$id' ");

        $p = $this->db->query("select * from pembelian_timbangan where id_pt='$id' ");
        if($p->num_rows() > 0){

            foreach($p->result() as $r){
                $id_pembelian = $r->id_pembelian;
            }
            $tot = $this->db->query("select sum(bobot) as b from pembelian_timbangan where id_pembelian='$id_pembelian' ");
            $total = 0;
            if($tot->num_rows() > 0){
                foreach($tot->result() as $t){
                    $total = $t->b;
                }
                $this->db->query("update pembelian set total_tonase = '$total' where id_pembelian='$id_pembelian' ");
            }

        }

        redirect(base_url('transaksi/pembelian')); 
        
    }

    function selesai(){
        $id = $this->input->post('id_selesai');
        
        $data = array("status"=>"Selesai");
        $where = array("id_pembelian"=>$id);

        $this->load->model("pembelian_model");
        $this->pembelian_model->update_data("pembelian",$data,$where);
        $p = $this->db->query("select * from pembelian where id_pembelian='$id' ");
        if($p->num_rows() > 0){
            foreach($p->result() as $r){
                $tanggal = $r->tanggal;
                $pembayaran_kuli = $r->total_tonase * $r->harga_kbk;
                $no_polisi = $r->no_polisi;
                $timbangan = $r->timbangan;
            }
            
            #-------------------------------------------------------------------#
            #Insert pengeluaran KULI ke tabel transaksi
            $id_transaksi = id_primary();
            $keterangan = "Kuli (".$no_polisi.")";
            $data = array("id_transaksi"=>$id_transaksi,
                        "nota"=>NULL,
                        "tanggal"=>$tanggal,
                        "keterangan"=>$keterangan,
                        "debit"=>0,
                        "kredit"=>$pembayaran_kuli,
                        "jenis"=>"Pengeluaran"
                    );
            $this->pembelian_model->simpan_data("transaksi",$data);
            #Insert ke tabel transaksi_pembelian
            $data = array("id_tpk"=>id_primary(),
                        "id_pembelian"=>$id,
                        "id_transaksi"=>$id_transaksi
                    );
            $this->pembelian_model->simpan_data("transaksi_pembelian",$data);

            #-------------------------------------------------------------------#
            #Insert pengeluaran TIMBANGAN ke tabel transaksi
            if($timbangan > 0){

            $id_transaksi = id_primary();
            $keterangan = "Timbangan (".$no_polisi.")";
            $data = array("id_transaksi"=>$id_transaksi,
                        "nota"=>NULL,
                        "tanggal"=>$tanggal,
                        "keterangan"=>$keterangan,
                        "debit"=>0,
                        "kredit"=>$timbangan,
                        "jenis"=>"Pengeluaran"
                    );
            $this->pembelian_model->simpan_data("transaksi",$data);
            #Insert ke tabel transaksi_pembelian
            $data = array("id_tpk"=>id_primary(),
                        "id_pembelian"=>$id,
                        "id_transaksi"=>$id_transaksi
                    );
            $this->pembelian_model->simpan_data("transaksi_pembelian",$data);

            }
            #-------------------------------------------------------------------#
            #Insert ke tabel utang_mutasi
            
            #-------------------------------------------------------------------#
            #Update akumulasi utang ke tabel pengirim
        }
        
       

        redirect(base_url('transaksi/pembelian')); 
        
    }

    function simpan(){
        $id = $this->input->post("id");
        $harga_satuan =$this->input->post("harga_satuan");
        $harga_kbk = $this->input->post("harga_kbk");
        $timbangan = $this->input->post("timbangan");
        
        $data = array("harga_satuan"=>$harga_satuan,"harga_kbk"=>$harga_kbk,"timbangan"=>$timbangan,"updated_at"=>date("Y-m-d H:i:s"));
        $where = array("id_pembelian"=>$id);
        $this->load->model("pembelian_model");
        $this->pembelian_model->update_data("pembelian",$data,$where);
        
        redirect(base_url('transaksi/pembelian')); 

    }

    function tambah(){
        $id = id_primary();
        $id_pembelian = $this->input->post("id_pembelian");
        $bobot = $this->input->post("bobot");

        $this->db->query("insert into pembelian_timbangan (id_pt, id_pembelian, bobot) values ('$id','$id_pembelian','$bobot')");
        $tot = $this->db->query("select sum(bobot) as b from pembelian_timbangan where id_pembelian='$id_pembelian' ");
        $total = 0;
        if($tot->num_rows() > 0){
            foreach($tot->result() as $t){
                $total = $t->b;
            }
        }
        $this->db->query("update pembelian set total_tonase = '$total' where id_pembelian='$id_pembelian' ");
       

    }


    function list_hapus(){
        $id = $this->uri->segment(3);
        
        $where = array("id_d_beli"=>$id);

        $this->load->model("pembelian_d_model");
        $this->pembelian_d_model->hapus_data("pembelian_d",$where);
        
        
        #update total
        $id_pembelian = $this->uri->segment(4);
        $this->load->model("pembelian_model");
        $t = $this->pembelian_model->cari_total($id_pembelian);
        if($t->num_rows() > 0){
            foreach($t->result() as $h){
                $total = $h->total;
            }
        }
        else
        {
            $total = 0;
        }
        $data = array("total"=>$total);
        $where = array("id_pembelian"=>$id_pembelian);
        $this->pembelian_model->update_data("pembelian",$data,$where);

        redirect(base_url('transaksi/pembelian/input/'.$this->enkripsi_url->encode($id_pembelian))); 
        
    }



    function cetak()
    {
        $id = $this->enkripsi_url->decode($this->uri->segment(4));
        $this->load->model("pembelian_model");

        $data["pembelian"] = $this->pembelian_model->tampil_data_edit($id);
        $data["keranjang"] = $this->db->query("select pembelian_d.* from pembelian_d where
        pembelian_d.id_pembelian='$id' ");
        $this->load->view("admin/transaksi/pembelian-cetak.php",$data);
    }


    function simpan_pembayaran(){
        #-----Simpan Pembayaran------#
        $this->load->model("pembelian_model");
        $id_bayar = $this->input->post('id_bayar');
        $tanggal = tgl_pecah($this->input->post('tanggal'));
        $bayar = uangPecah($this->input->post('bayar'));
        $total_tagihan = uangPecah($this->input->post('total_pembelian'));

            $id_pb = id_primary();
            $data = array("id_pb"=>$id_pb,
                        "id_pembelian"=>$id_bayar,
                        "tanggal"=>$tanggal,
                        "bayar"=>$bayar
                    );
            $this->pembelian_model->simpan_data("pembelian_bayar",$data);
        #-----Simpan Pembayaran------#

        #-----Kontrol Pelunasan------#
        $total_bayar = 0;
        $t = $this->db->query("select sum(bayar) as b  from pembelian_bayar where id_pembelian='$id_bayar' ");
        if($t->num_rows() > 0){
            foreach($t->result() as $r){
                $total_bayar = $r->b;
            }
        }
        if($total_bayar >= $total_tagihan){
            $this->db->query("update pembelian set status_bayar='Lunas' where id_pembelian='$id_bayar' ");
        } else {
            $this->db->query("update pembelian set status_bayar='Belum Lunas' where id_pembelian='$id_bayar' ");
        }
        #-----Kontrol Pelunasan-----#

    }

    function riwayat_pembayaran($id)
    {
        $this->load->model("pembelian_model");

        $data["pembelian"] = $this->db->query("select * from pembelian, pengirim where pembelian.id_pembelian='$id' and pembelian.id_pengirim = pengirim.id_pengirim");
        $data["pembayaran"] = $this->db->query("select * from pembelian_bayar where id_pembelian='$id' order by tanggal asc, created_at asc");
        $this->load->view("admin/transaksi/pembelian-history-pembayaran-tampil.php",$data);
    }

	
}
?>