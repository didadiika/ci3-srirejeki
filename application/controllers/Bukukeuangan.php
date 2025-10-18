<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once (dirname(__FILE__) . "/BaseController.php");



class Bukukeuangan extends BaseController{

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
        $this->load->view("admin/transaksi/buku-keuangan.php");
        $this->load->view("admin/template/footer.php");
        #Menampilkan halaman suplier dan mem passing variabel data#
    }
    
    function tampil(){
		#Mengambil data kain secara serverside#
		$this->load->model("transaksi_model");
		$d = $this->transaksi_model->make_datatables();
		$data = array();
		$start = isset($_GET["start"]) ? $_GET["start"] : 0;
		$no = $start + 1;
		foreach($d as $r){
		    
			$sub_array = array();
			$sub_array[] = $no++;
            $sub_array[] = tgl_pecah($r->tanggal);
            $sub_array[] = $r->keterangan;
            $sub_array[] = uang($r->debit);
            $sub_array[] = uang($r->kredit);
            $sub_array[] = $r->jenis;
                if($r->jenis == "Kas Masuk" || $r->jenis == "Kas Keluar"){
                    $sub_array[] = '
                    <div class="btn-group">
                    <button type="button" class="btn btn-default">Pilih</button>
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                        <span class="caret"></span>
                        <span class="sr-only">Toggle Dropdown</span>
                    </button>
                        <ul class="dropdown-menu" role="menu">
                        <li><a href="javascript:;" class="item_hapus" data="'.$r->id_transaksi.'" >Hapus</a></li>
                        </ul>
                    </div>';
                } else {
                    $sub_array[] = '';
                }
                
                
            
			
			$data[] = $sub_array;
		}
		#Mengambil data kategori di tabel kategori dan memasukkan ke variabel data#
		$draw = "";
		if(isset($_POST["draw"])){$draw = $_POST["draw"];}

			$output = array(
				"draw" 				=> intval($draw),
				"recordsTotal" 		=> $this->transaksi_model->get_all_data(),
				"recordsFiltered" 	=> $this->transaksi_model->get_filtered_data(),
				"data"				=> $data
		
		);

		echo json_encode($output);
	}

   

    function hapus(){
        $id = $this->input->post('id');
        $where = array("id_transaksi"=>$id);

        $this->load->model("transaksi_model");
        $this->transaksi_model->hapus_data("transaksi",$where);
        

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

 

    function simpan(){
        $id = id_primary();
        $debit = uangPecah($this->input->post("debit"));
        $kredit = uangPecah($this->input->post("kredit"));
        $keterangan = $this->input->post("keterangan");
        $tanggal = tgl_pecah($this->input->post("tanggal"));
        $jenis = ($debit > 0) ? "Kas Masuk" : "Kas Keluar" ;
        
        $data = array("id_transaksi"=>$id,"tanggal"=>$tanggal,"keterangan"=>$keterangan,"debit"=>$debit,"kredit"=>$kredit,"jenis"=>$jenis);
        $this->load->model("transaksi_model");
        $this->transaksi_model->simpan_data("transaksi",$data);
        
         

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
        $this->load->model("transaksi_model");
        $t = $this->transaksi_model->cari_total($id_pembelian);
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
        $this->transaksi_model->update_data("pembelian",$data,$where);

        redirect(base_url('transaksi/pembelian/input/'.$this->enkripsi_url->encode($id_pembelian))); 
        
    }



    function cetak()
    {
        $id = $this->enkripsi_url->decode($this->uri->segment(4));
        $this->load->model("transaksi_model");

        $data["pembelian"] = $this->transaksi_model->tampil_data_edit($id);
        $data["keranjang"] = $this->db->query("select pembelian_d.* from pembelian_d where
        pembelian_d.id_pembelian='$id' ");
        $this->load->view("admin/transaksi/pembelian-cetak.php",$data);
    }

	
}
?>