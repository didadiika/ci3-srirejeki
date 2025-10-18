<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once (dirname(__FILE__) . "/BaseController.php");

class Laporan extends BaseController{

	function __construct(){
		parent::__construct();

		$cek_login = $this->session->userdata('authenticated');
        if ($cek_login != true) {
			redirect( base_url('login') );
        }

	}

	
	function index(){
		

    }

    

    

    function laporan_pembelian(){
       
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
        $data["pengirim"] = $this->db->query("select * from pengirim where deleted_at is null");
        $this->load->view("admin/laporan/laporan-pembelian.php",$data);
        $this->load->view("admin/template/footer.php");

    }

    function laporan_pembelian_tampil(){
        $this->load->model("transaksi_model");
        $id_pengirim = $this->input->post("id_pengirim");
        $dari = $this->input->post("dari");
		$sampai = $this->input->post("sampai");
        $nama_bulan = array(1=>"Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
		$data["lap"] = array(
					"dari"=>substr($dari,0,2)."-".$nama_bulan[(int)substr($dari,3,2)]."-".substr($dari,6,4),
					"sampai"=>substr($sampai,0,2)."-".$nama_bulan[(int)substr($sampai,3,2)]."-".substr($sampai,6,4));

        if($id_pengirim == "*"){
            $data["gaji"] = $this->db->query("select * from pembelian, pengirim where pembelian.id_pengirim = pengirim.id_pengirim and 
            (pembelian.tanggal between '".tgl_pecah($dari)."' and '".tgl_pecah($sampai)."' ) and 
            pembelian.status='Selesai' and 
            pembelian.deleted_at is null order by pembelian.tanggal asc");
            $data["pengirim"] = "Semua Pengirim";
            $data["sudah_bayar"] = $this->db->query("select sum(pembelian_bayar.bayar) as paid from pembelian, pembelian_bayar where 
            pembelian.id_pembelian = pembelian_bayar.id_pembelian and 
            (pembelian.tanggal between '".tgl_pecah($dari)."' and '".tgl_pecah($sampai)."' ) and 
            pembelian.status='Selesai' and 
            pembelian.deleted_at is null ");
        } else {
            $pengirim = $this->input->post("pengirim");
                $i = 0;
                foreach($pengirim as $x){
                    if($i == 0){
                        $d = "'".$x."'";
                    }else{
                        $d .= ",'".$x."'";
                    }
                    $i++;
                }

            $kn = $this->db->query("select * from pengirim where id_pengirim in (".$d.")");
            if($kn->num_rows() > 0)
            {
                
                $h = 0 ;
                foreach($kn->result() as $nn){
                    if($h == 0){
                        $d_name = $nn->nama_pengirim;
                    }else{
                        $d_name .= ",".$nn->nama_pengirim;
                    }
                    $h++;
                }
                $data["pengirim"] = $d_name;
            }

            $data["gaji"] = $this->db->query("select * from pembelian, pengirim where pembelian.id_pengirim = pengirim.id_pengirim and 
            (pembelian.tanggal between '".tgl_pecah($dari)."' and '".tgl_pecah($sampai)."' ) and 
            pembelian.id_pengirim in (".$d.") and
            pembelian.status='Selesai' and 
            pembelian.deleted_at is null order by pembelian.tanggal asc");

            $data["sudah_bayar"] = $this->db->query("select sum(pembelian_bayar.bayar) as paid from pembelian, pembelian_bayar where 
            pembelian.id_pembelian = pembelian_bayar.id_pembelian and 
            (pembelian.tanggal between '".tgl_pecah($dari)."' and '".tgl_pecah($sampai)."' ) and 
            pembelian.id_pengirim in (".$d.") and
            pembelian.status='Selesai' and 
            pembelian.deleted_at is null ");
        }
		#Menampilkan halaman#
		$this->load->view("admin/laporan/laporan-pembelian-tampil.php",$data);
        #Menampilkan halaman#
	}


    function laporan_penjualan(){
       
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
        $data["pelanggan"] = $this->db->query("select * from pelanggan where deleted_at is null");
        $this->load->view("admin/laporan/laporan-penjualan.php",$data);
        $this->load->view("admin/template/footer.php");

    }

    function laporan_penjualan_tampil(){
		$jenis = $this->input->post("jenis");
        $pelanggan = $this->input->post("id_pelanggan");
		$dari = $this->input->post("dari");
		$sampai = $this->input->post("sampai");
        $nama_bulan = array(1=>"Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
        
        $data["jenis"] = $jenis;
		$data["lap"] = array(
					"dari"=>substr($dari,0,2)."-".$nama_bulan[(int)substr($dari,3,2)]."-".substr($dari,6,4),
					"sampai"=>substr($sampai,0,2)."-".$nama_bulan[(int)substr($sampai,3,2)]."-".substr($sampai,6,4));
        $this->load->model("transaksi_model");
		

        if($jenis == "Tampil per Nota")
        {
            if($pelanggan == "*"){
                $data["gaji"] = $this->db->query("select * from pelanggan, invoice where invoice.id_pelanggan = pelanggan.id_pelanggan and 
                (invoice.tanggal between '".tgl_pecah($dari)."' and '".tgl_pecah($sampai)."' ) and 
                invoice.status='Selesai' and 
                invoice.deleted_at is null order by invoice.tanggal asc");
                $data["pelanggan"] = "Semua Pelanggan";
                } else {
                $data["gaji"] = $this->db->query("select * from pelanggan, invoice where invoice.id_pelanggan = pelanggan.id_pelanggan and 
                (invoice.tanggal between '".tgl_pecah($dari)."' and '".tgl_pecah($sampai)."' ) and 
                invoice.id_pelanggan='$pelanggan' and
                invoice.status='Selesai' and 
                invoice.deleted_at is null order by invoice.tanggal asc");
                $pen = $this->db->query("select * from pelanggan where id_pelanggan='$pelanggan' ");
                if($pen->num_rows() > 0){
                    foreach($pen->result() as $p){
                        $data["pelanggan"] = $p->nama_pelanggan;
                    }
                }
                    
                }
            #Menampilkan halaman#
            $this->load->view("admin/laporan/laporan-penjualan-per-nota-tampil.php",$data);
            #Menampilkan halaman#
        } else if($jenis == "Tampil Rinci"){
            if($pelanggan == "*"){
                $data["gaji"] = $this->db->query("select * from pelanggan, invoice, invoice_d where invoice.id_pelanggan = pelanggan.id_pelanggan and 
                (invoice.tanggal between '".tgl_pecah($dari)."' and '".tgl_pecah($sampai)."' ) and 
                invoice.id_invoice = invoice_d.id_invoice and
                invoice.status='Selesai' and 
                invoice.deleted_at is null order by invoice.tanggal asc");
                $data["pelanggan"] = "Semua Pelanggan";
                } else {
                $data["gaji"] = $this->db->query("select * from pelanggan, invoice, invoice_d where invoice.id_pelanggan = pelanggan.id_pelanggan and 
                (invoice.tanggal between '".tgl_pecah($dari)."' and '".tgl_pecah($sampai)."' ) and 
                invoice.id_invoice = invoice_d.id_invoice and
                invoice.id_pelanggan='$pelanggan' and
                invoice.status='Selesai' and 
                invoice.deleted_at is null order by invoice.tanggal asc");
                $pen = $this->db->query("select * from pelanggan where id_pelanggan='$pelanggan' ");
                if($pen->num_rows() > 0){
                    foreach($pen->result() as $p){
                        $data["pelanggan"] = $p->nama_pelanggan;
                    }
                }
                    
                }
            #Menampilkan halaman#
            $this->load->view("admin/laporan/laporan-penjualan-rinci-tampil.php",$data);
            #Menampilkan halaman#
        }		
	}

    function laporan_piutang(){
       
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
        $data["pelanggan"] = $this->db->query("select * from pelanggan where deleted_at is null");
        $this->load->view("admin/laporan/laporan-piutang.php",$data);
        $this->load->view("admin/template/footer.php");

    }

    function laporan_piutang_tampil(){
		$pelanggan = $this->input->post("id_pelanggan");
		
        $this->load->model("invoice_model");
		
		
		$data["gaji"] = $this->db->query("select * from pelanggan, invoice where invoice.id_pelanggan = pelanggan.id_pelanggan and 
        invoice.id_pelanggan='$pelanggan' and
        invoice.status='Selesai' and 
        invoice.status_bayar='Belum Lunas' and
        invoice.deleted_at is null order by invoice.tanggal asc");
        $pen = $this->db->query("select * from pelanggan where id_pelanggan='$pelanggan' ");
        if($pen->num_rows() > 0){
            foreach($pen->result() as $p){
                $data["pelanggan"] = $p->nama_pelanggan;
            }
        }
		    
		#Menampilkan halaman#
		$this->load->view("admin/laporan/laporan-piutang-tampil.php",$data);
        #Menampilkan halaman#
	}


    function laporan_buku_keuangan(){
       
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
        $this->load->view("admin/laporan/laporan-buku-keuangan.php");
        $this->load->view("admin/template/footer.php");

    }

    function laporan_buku_keuangan_tampil(){
		$dari = $this->input->post("dari");
		$sampai = $this->input->post("sampai");
        $nama_bulan = array(1=>"Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
		$data["lap"] = array(
					"dari"=>substr($dari,0,2)."-".$nama_bulan[(int)substr($dari,3,2)]."-".substr($dari,6,4),
					"sampai"=>substr($sampai,0,2)."-".$nama_bulan[(int)substr($sampai,3,2)]."-".substr($sampai,6,4));
        $this->load->model("transaksi_model");
		
		

		$data["gaji"] = $this->db->query("select * from transaksi where
        (transaksi.tanggal between '".tgl_pecah($dari)."' and '".tgl_pecah($sampai)."' ) and 
        transaksi.deleted_at is null order by transaksi.tanggal asc, transaksi.created_at asc");
        $t = $this->db->query("select sum(debit) as db, sum(kredit) as kr from transaksi where
        transaksi.tanggal < '".tgl_pecah($dari)."' and 
        transaksi.deleted_at is null ");

        $saldo_awal = 0;
        
        if($t->num_rows() > 0){
            foreach($t->result() as $r){
                $saldo_db = $r->db;
                $saldo_kr = $r->kr;
            }
            $saldo_awal = $saldo_db - $saldo_kr;
        }
        $data["saldo"] = $saldo_awal;
		
		    
		

		#Menampilkan halaman#
		$this->load->view("admin/laporan/laporan-buku-keuangan-tampil.php",$data);
        #Menampilkan halaman#
	}


    function get_pengirim(){
        echo"<label for='ProductCode'>Pilih Pengirim</label>";
        echo"<select class='js-example-basic-multiple  form-control' name='pengirim[]' multiple='multiple' required>";
        echo "</select>";
    }

}
?>