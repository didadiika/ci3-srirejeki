<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once (dirname(__FILE__) . "/BaseController.php");

class Data extends BaseController{

	function __construct(){
		parent::__construct();

		$cek_login = $this->session->userdata('authenticated');
        if ($cek_login != true) {
			redirect( base_url('login') );
        }

	}

	
	function index(){
		
        	/*
			*	Menampilkan view master template dan halaman beranda
			*
			*/
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
        	$this->load->view("admin/beranda.php");
        	$this->load->view("admin/template/footer.php");
        	/*
			*	Menampilkan view master template dan halaman beranda
			*
			*/
    }


    function pengirim(){
        
        #Mengambil data toko di tabel suplier dan memasukkan ke variabel data#
        $this->load->model("pengirim_model");
        
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
        $this->load->view("admin/data/pengirim.php");
        $this->load->view("admin/template/footer.php");
        #Menampilkan halaman suplier dan mem passing variabel data#
    }
    
    function pengirim_tampil(){
		#Mengambil data kain secara serverside#
		$this->load->model("pengirim_model");
		$d = $this->pengirim_model->make_datatables();
		$data = array();
		$start = isset($_GET["start"]) ? $_GET["start"] : 0;
		$no = $start + 1;
		foreach($d as $r){
		    
			$sub_array = array();
			$sub_array[] = $no++;
            $sub_array[] = $r->nama_pengirim;
			$sub_array[] = '
            <a class="btn btn-sm btn-warning item_edit" href="javascript:void(0)" title="Edit"
                nama="'.$r->nama_pengirim.'" data="'.$r->id_pengirim.'" ><i class="fa fa-pencil"></i> Edit</a>
            <a href="javascript:;" class="btn btn-sm btn-danger item_hapus" nama="'.$r->nama_pengirim.'" data="'.$r->id_pengirim.'">
            <i class="fa fa-trash"></i> Hapus</a>';
			$data[] = $sub_array;
		}
		#Mengambil data kategori di tabel kategori dan memasukkan ke variabel data#
		$draw = "";
		if(isset($_POST["draw"])){$draw = $_POST["draw"];}

			$output = array(
				"draw" 				=> intval($draw),
				"recordsTotal" 		=> $this->pengirim_model->get_all_data(),
				"recordsFiltered" 	=> $this->pengirim_model->get_filtered_data(),
				"data"				=> $data
		
		);

		echo json_encode($output);
	}


    function pengirim_simpan(){
        $nama = $this->input->post("nama");
        
        $id = id_primary();
        $data = array("id_pengirim"=>$id,"nama_pengirim"=>$nama);
        $this->load->model("pengirim_model");
        $this->pengirim_model->simpan_data("pengirim",$data);
        
        redirect(base_url('data/pengirim')); 

    }

    function pengirim_update(){
        $this->load->model("pengirim_model");
        $id = $this->input->post("id_update");
        
        $nama = $this->input->post("nama_update");
        

        $data = array("nama_pengirim"=>$nama);
        $where = array("id_pengirim"=>$id);

        
        $this->pengirim_model->update_data("pengirim",$data,$where);

        redirect(base_url('data/pengirim')); 
        
    }
    
    function pengirim_hapus(){
        $id = $this->input->post("id");
        
        $data = array("deleted_at"=>date("Y-m-d H:i:s"));
        $where = array("id_pengirim"=>$id);

        $this->load->model("pengirim_model");
        $this->pengirim_model->update_data("pengirim",$data,$where);

        redirect(base_url('data/pengirim')); 
        
    }








    function pelanggan(){
        
        #Mengambil data toko di tabel suplier dan memasukkan ke variabel data#
        $this->load->model("pelanggan_model");
        
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
        $this->load->view("admin/data/pelanggan.php");
        $this->load->view("admin/template/footer.php");
        #Menampilkan halaman suplier dan mem passing variabel data#
    }
    
    function pelanggan_tampil(){
		#Mengambil data kain secara serverside#
		$this->load->model("pelanggan_model");
		$d = $this->pelanggan_model->make_datatables();
		$data = array();
		$start = isset($_GET["start"]) ? $_GET["start"] : 0;
		$no = $start + 1;
		foreach($d as $r){
		    
			$sub_array = array();
			$sub_array[] = $no++;
            $sub_array[] = $r->nama_pelanggan;
            $sub_array[] = $r->alamat;
            $sub_array[] = $r->telepon;
			$sub_array[] = '
            <a class="btn btn-sm btn-warning item_edit" href="javascript:void(0)" title="Edit"
                nama="'.$r->nama_pelanggan.'" alamat="'.$r->alamat.'" telepon="'.$r->telepon.'" data="'.$r->id_pelanggan.'" ><i class="fa fa-pencil"></i> Edit</a>
            <a href="javascript:;" class="btn btn-sm btn-danger item_hapus" nama="'.$r->nama_pelanggan.'"  data="'.$r->id_pelanggan.'">
            <i class="fa fa-trash"></i> Hapus</a>';
			$data[] = $sub_array;
		}
		#Mengambil data kategori di tabel kategori dan memasukkan ke variabel data#
		$draw = "";
		if(isset($_POST["draw"])){$draw = $_POST["draw"];}

			$output = array(
				"draw" 				=> intval($draw),
				"recordsTotal" 		=> $this->pelanggan_model->get_all_data(),
				"recordsFiltered" 	=> $this->pelanggan_model->get_filtered_data(),
				"data"				=> $data
		
		);

		echo json_encode($output);
	}


    function pelanggan_simpan(){
        $nama = $this->input->post("nama");
        $alamat = $this->input->post("alamat");
        $telepon = $this->input->post("telepon");
        
        $id = id_primary();
        $data = array("id_pelanggan"=>$id,"nama_pelanggan"=>$nama,"alamat"=>$alamat,"telepon"=>$telepon);
        $this->load->model("pelanggan_model");
        $this->pelanggan_model->simpan_data("pelanggan",$data);
        
        redirect(base_url('data/pelanggan')); 

    }

    function pelanggan_update(){
        $this->load->model("pelanggan_model");
        $id = $this->input->post("id_update");
        
        $nama = $this->input->post("nama_update");
        $alamat = $this->input->post("alamat_update");
        $telepon = $this->input->post("telepon_update");
        

        $data = array("nama_pelanggan"=>$nama,"alamat"=>$alamat,"telepon"=>$telepon);
        $where = array("id_pelanggan"=>$id);

        
        $this->pelanggan_model->update_data("pelanggan",$data,$where);

        redirect(base_url('data/pelanggan')); 
        
    }
    
    function pelanggan_hapus(){
        $id = $this->input->post("id");
        
        $data = array("deleted_at"=>date("Y-m-d H:i:s"));
        $where = array("id_pelanggan"=>$id);

        $this->load->model("pelanggan_model");
        $this->pelanggan_model->update_data("pelanggan",$data,$where);

        redirect(base_url('data/pelanggan')); 
        
    }


    function karyawan(){
        
        #Mengambil data toko di tabel suplier dan memasukkan ke variabel data#
        $this->load->model("karyawan_model");
        
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
        $this->load->view("admin/data/karyawan.php");
        $this->load->view("admin/template/footer.php");
        #Menampilkan halaman suplier dan mem passing variabel data#
    }


    function karyawan_tampil(){
		#Mengambil data kain secara serverside#
		$this->load->model("karyawan_model");
		$d = $this->karyawan_model->make_datatables();
		$data = array();
		$start = isset($_GET["start"]) ? $_GET["start"] : 0;
		$no = $start + 1;
		foreach($d as $r){
		    
			$sub_array = array();
			$sub_array[] = $no++;
            $sub_array[] = $r->nama_karyawan;
			$sub_array[] = '
            <a class="btn btn-sm btn-warning item_edit" href="javascript:void(0)" title="Edit"
                nama="'.$r->nama_karyawan.'" data="'.$r->id_karyawan.'" ><i class="fa fa-pencil"></i> Edit</a>
            <a href="javascript:;" class="btn btn-sm btn-danger item_hapus" nama="'.$r->nama_karyawan.'"  data="'.$r->id_karyawan.'">
            <i class="fa fa-trash"></i> Hapus</a>';
			$data[] = $sub_array;
		}
		#Mengambil data kategori di tabel kategori dan memasukkan ke variabel data#
		$draw = "";
		if(isset($_POST["draw"])){$draw = $_POST["draw"];}

			$output = array(
				"draw" 				=> intval($draw),
				"recordsTotal" 		=> $this->karyawan_model->get_all_data(),
				"recordsFiltered" 	=> $this->karyawan_model->get_filtered_data(),
				"data"				=> $data
		
		);

		echo json_encode($output);
	}


    function karyawan_simpan(){
        $nama = $this->input->post("nama");
        
        $id = id_primary();
        $data = array("id_karyawan"=>$id,"nama_karyawan"=>$nama);
        $this->load->model("karyawan_model");
        $this->karyawan_model->simpan_data("karyawan",$data);
        
        redirect(base_url('data/karyawan')); 

    }

    function karyawan_update(){
        $this->load->model("karyawan_model");
        $id = $this->input->post("id_update");
        
        $nama = $this->input->post("nama_update");
        

        $data = array("nama_karyawan"=>$nama);
        $where = array("id_karyawan"=>$id);

        
        $this->karyawan_model->update_data("karyawan",$data,$where);

        redirect(base_url('data/karyawan')); 
        
    }
    
    function karyawan_hapus(){
        $id = $this->input->post("id");
        
        $data = array("deleted_at"=>date("Y-m-d H:i:s"));
        $where = array("id_karyawan"=>$id);

        $this->load->model("karyawan_model");
        $this->karyawan_model->update_data("karyawan",$data,$where);

        redirect(base_url('data/karyawan')); 
        
    }

    function karyawan_cetak_absensi()
    {
        $data["karyawan"] = $this->db->query("select * from karyawan where deleted_at is null order by nama_karyawan asc");
        $this->load->view("admin/data/karyawan-absensi-cetak.php",$data);
    }

}
?>