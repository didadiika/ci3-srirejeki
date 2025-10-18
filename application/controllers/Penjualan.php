<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once (dirname(__FILE__) . "/BaseController.php");

class Penjualan extends BaseController{

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
        $this->load->view("admin/transaksi/penjualan.php");
        $this->load->view("admin/template/footer.php");
        #Menampilkan halaman suplier dan mem passing variabel data#
    }
    
    function tampil(){
		#Mengambil data kain secara serverside#
		$this->load->model("penjualan_model");
		$kain = $this->penjualan_model->make_datatables();
		$data = array();
		$start = isset($_POST["start"]) ? $_POST["start"] : 0;
		$no = $start + 1;
		foreach($kain as $r){
		    
			$sub_array = array();
			$sub_array[] = $no++;
            $sub_array[] = tgl_pecah($r->tanggal);
            $sub_array[] = $r->no_nota;
            $sub_array[] = $r->nama_pelanggan;
            $sub_array[] = uang($r->total);
            
            if($r->status == "Proses")
            {
                $sub_array[] = '<span class="badge btn-warning">Proses</span>';
                $sub_array[] = '<a class="btn btn-xs btn-warning" href="'. base_url('transaksi/penjualan/input/'.$this->enkripsi_url->encode($r->id_penjualan)).'" title="Edit">Edit</a>
            <a href="javascript:;" class="btn btn-xs btn-danger item_hapus" data="'.$this->enkripsi_url->encode($r->id_penjualan).'">Hapus</a>';
            }
            else if($r->status == "Simpan")
            {
                $sub_array[] = '<span class="badge btn-success">Simpan</span>';
                $sub_array[] = '<a class="btn btn-xs btn-warning" href="'. base_url('transaksi/penjualan/input/'.$this->enkripsi_url->encode($r->id_penjualan)).'" title="Edit">Edit</a>
                <a href="javascript:;" class="btn btn-xs btn-info item_selesai" data-selesai="'.$this->enkripsi_url->encode($r->id_penjualan).'">Selesai</a>
                <a href="javascript:;" class="btn btn-xs btn-danger item_hapus" data="'.$this->enkripsi_url->encode($r->id_penjualan).'" >Hapus</a>';
            }
            else
            {
                $sub_array[] = '<span class="badge btn-primary">Selesai</span>';
                $sub_array[] = '<a class="btn btn-xs btn-success" target="_blank" href="'. base_url('transaksi/penjualan/cetak/'.$this->enkripsi_url->encode($r->id_penjualan)).'" title="Cetak">Lihat</a>
            <a href="javascript:;" class="btn btn-xs btn-danger item_hapus" data="'.$this->enkripsi_url->encode($r->id_penjualan).'">Hapus</a>';
            }
			
			$data[] = $sub_array;
		}
		#Mengambil data kategori di tabel kategori dan memasukkan ke variabel data#
		$draw = "";
		if(isset($_POST["draw"])){$draw = $_POST["draw"];}

			$output = array(
				"draw" 				=> intval($draw),
				"recordsTotal" 		=> $this->penjualan_model->get_all_data(),
				"recordsFiltered" 	=> $this->penjualan_model->get_filtered_data(),
				"data"				=> $data
		
		);

		echo json_encode($output);
	}

    function input(){
        
        $this->load->model("pelanggan_model");
        $data["pelanggan"] = $this->pelanggan_model->pelanggan_all();
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

        
        if($this->enkripsi_url->decode($this->uri->segment(4)))
        {
        $id = $this->enkripsi_url->decode($this->uri->segment(4));
        $this->load->model("penjualan_model");
        $this->load->model("barang_model");
        $this->load->model("ukuran_model");
        $data["barang"] = $this->barang_model->barang_all();
        $data["ukuran"] = $this->ukuran_model->ukuran_all();
        $data["penjualan"] = $this->penjualan_model->tampil_data_edit($id);
        $data["keranjang"] = $this->penjualan_model->tampil_keranjang($id);
        $this->load->view("admin/transaksi/penjualan-inputing.php",$data);
        }
        else
        {
        
        $this->load->model("pegawai_model");
        $data["pegawai"] = $this->pegawai_model->tampil_pegawai();
        $this->load->view("admin/transaksi/penjualan-input.php",$data);
        }
        $this->load->view("admin/template/footer.php");
        #Menampilkan halaman suplier dan mem passing variabel data#
    }


    function buat(){
        
        $id_user = $this->session->userdata('id_user');
        $tanggal = tgl_pecah($this->input->post("tanggal"));
        $id_pelanggan = $this->input->post("id_pelanggan");
        $no_nota = $this->input->post("no_nota");
        $keterangan = $this->input->post("keterangan");
        $id = id_primary();
        $data = array("id_penjualan"=>$id,"id_user"=>$id_user,"tanggal"=>$tanggal,"id_pelanggan"=>$id_pelanggan,"no_nota"=>$no_nota,"keterangan"=>$keterangan,
    "status"=>"Proses");

        $this->load->model("penjualan_model");
        $this->penjualan_model->simpan_data("penjualan",$data);

        redirect(base_url('transaksi/penjualan/input/'.$this->enkripsi_url->encode($id)));
        
    }

    function hapus(){
        $id = $this->enkripsi_url->decode($this->input->post('id_hapus'));
        
        $data = array("deleted_at"=>date("Y-m-d H:i:s"));
        $where = array("id_penjualan"=>$id);

        $this->load->model("penjualan_model");
        $this->penjualan_model->update_data("penjualan",$data,$where);
        

        redirect(base_url('transaksi/penjualan')); 
        
    }

    function simpan(){
        $id = $this->enkripsi_url->decode($this->input->post("id"));
        
        $data = array("status"=>"Simpan","updated_at"=>date("Y-m-d H:i:s"));
        $where = array("id_penjualan"=>$id);
        $this->load->model("penjualan_model");
        $this->penjualan_model->update_data("penjualan",$data,$where);
        
        redirect(base_url('transaksi/penjualan')); 

    }

    function tambah(){
        $id = id_primary();
        $id_penjualan = $this->input->post("id_penjualan");
        $id_barang = $this->input->post("id_barang");
        $jumlah = uangPecahDecimal($this->input->post("jumlah"));
        $harga = uangPecah($this->input->post("harga"));
        $sub_total = uangPecah($this->input->post("sub_total"));

        $data = array("id_d_jual"=>$id,"id_penjualan"=>$id_penjualan,"id_barang"=>$id_barang,"jumlah"=>$jumlah,"harga"=>$harga,"sub_total"=>$sub_total);
        $this->load->model("penjualan_d_model");
        $this->penjualan_d_model->simpan_data("penjualan_d",$data);


        #update total
        $this->load->model("penjualan_model");
        $t = $this->penjualan_model->cari_total($id_penjualan);
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
        $where = array("id_penjualan"=>$id_penjualan);
        $this->penjualan_model->update_data("penjualan",$data,$where);
        redirect(base_url('transaksi/penjualan/input/'.$this->enkripsi_url->encode($id_penjualan))); 

    }


    function list_hapus(){
        $id = $this->uri->segment(3);
        
        $where = array("id_d_jual"=>$id);

        $this->load->model("penjualan_d_model");
        $this->penjualan_d_model->hapus_data("penjualan_d",$where);
        
        
        #update total
        $id_penjualan = $this->uri->segment(4);
        $this->load->model("penjualan_model");
        $t = $this->penjualan_model->cari_total($id_penjualan);
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
        $where = array("id_penjualan"=>$id_penjualan);
        $this->penjualan_model->update_data("penjualan",$data,$where);

        redirect(base_url('transaksi/penjualan/input/'.$this->enkripsi_url->encode($id_penjualan))); 
        
    }


    function selesai(){
        $id = $this->enkripsi_url->decode($this->input->post('id_selesai'));
        
        $data = array("status"=>"Selesai");
        $where = array("id_penjualan"=>$id);

        $this->load->model("penjualan_model");
        $this->penjualan_model->update_data("penjualan",$data,$where);
        

        redirect(base_url('transaksi/penjualan')); 
        
    }


    function cetak()
    {
        $id = $this->enkripsi_url->decode($this->uri->segment(4));
        $this->load->model("penjualan_model");

        $data["penjualan"] = $this->penjualan_model->tampil_data_edit($id);
        $data["keranjang"] = $this->db->query("select barang.nama_barang, penjualan_d.* from penjualan_d, barang where
        barang.id_barang = penjualan_d.id_barang and 
        penjualan_d.id_penjualan='$id' ");
        $this->load->view("admin/transaksi/penjualan-cetak.php",$data);
    }

	
}
?>