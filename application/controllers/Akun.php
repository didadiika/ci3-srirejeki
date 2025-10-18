<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once (dirname(__FILE__) . "/BaseController.php");

class Akun extends BaseController{
	
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
            if($level == "Superadmin")
            {
				$this->load->view("superadmin/template/header.php");
                $this->load->view("superadmin/template/menu.php");
                $this->load->view("superadmin/beranda.php");
        	    $this->load->view("superadmin/template/footer.php");

            } else if($level == "Admin"){
				$this->load->view("admin/template/header.php");
                $this->load->view("admin/template/menu.php");
                $this->load->view("admin/beranda.php");
        	    $this->load->view("admin/template/footer.php");
            }
        	
        	/*
			*	Menampilkan view master template dan halaman beranda
			*
			*/
        
		
	}

	function ganti_password(){
        
        #Mengambil data toko di tabel suplier dan memasukkan ke variabel data#
        $this->load->model("akun_model");
        $data["user"] = $this->akun_model->tampil_data_edit($this->session->id_user);
        #Mengambil data toko di tabel suplier dan memasukkan ke variabel data#

		#Menampilkan halaman suplier dan mem passing variabel data#
        $level = $this->session->level;
        if($level == "Superadmin")
        {
            $this->load->view("superadmin/template/header.php");
			$this->load->view("superadmin/template/menu.php");
			$this->load->view("superadmin/akun/ganti-password.php",$data);

        } else if($level == "Admin"){
            $this->load->view("admin/template/header.php");
			$this->load->view("admin/template/menu.php");
			$this->load->view("admin/akun/ganti-password.php",$data);
        }
        
        $this->load->view("admin/template/footer.php");
        #Menampilkan halaman suplier dan mem passing variabel data#
	}
    
    function ganti_password_update(){
        $this->load->model("akun_model");
        $id_user = $this->session->id_user;
        $pass1 = $this->input->post("pass1");
        $pass2 = $this->input->post("pass2");
        $pass3 = $this->input->post("pass3");

        
        $dt = $this->akun_model->tampil_data_edit($id_user);
        if($dt->num_rows() > 0)
        {
            foreach($dt->result() as $d){
                $password_sistem = $d->password;
            }
            if(!password_verify($pass1,$password_sistem))
            {
                redirect(base_url('akun/ganti_password_pass_old_error')); 
            }
            else if($pass2 != $pass3)
            {
                redirect(base_url('akun/ganti_password_pass_not_same')); 
            }
            else
            {
                $pass3 = password_hash($pass3,PASSWORD_DEFAULT);
                $data = array("password"=>$pass3);
                $where = array("id_user"=>$id_user);
                $this->akun_model->update_data("user",$data,$where);
                redirect(base_url('akun/ganti_password_success')); 
            }
            
        }

       
        redirect(base_url('akun/ganti_password_success'));
        
        
    }

    function ganti_password_pass_old_error(){
        
        #Mengambil data toko di tabel suplier dan memasukkan ke variabel data#
        $this->load->model("akun_model");
        $data["user"] = $this->akun_model->tampil_data_edit($this->session->id_user);
        #Mengambil data toko di tabel suplier dan memasukkan ke variabel data#

		#Menampilkan halaman suplier dan mem passing variabel data#
        $level = $this->session->level;
        if($level == "Superadmin")
        {
            $this->load->view("superadmin/template/header.php");
			$this->load->view("superadmin/template/menu.php");
			$this->load->view("superadmin/akun/ganti-password-pass-old-error.php",$data);

        } else if($level == "Admin"){
            $this->load->view("admin/template/header.php");
			$this->load->view("admin/template/menu.php");
			$this->load->view("admin/akun/ganti-password-pass-old-error.php",$data);
        }
        
        $this->load->view("admin/template/footer.php");
        #Menampilkan halaman suplier dan mem passing variabel data#
    }
    
    function ganti_password_pass_not_same(){
        
        #Mengambil data toko di tabel suplier dan memasukkan ke variabel data#
        $this->load->model("akun_model");
        $data["user"] = $this->akun_model->tampil_data_edit($this->session->id_user);
        #Mengambil data toko di tabel suplier dan memasukkan ke variabel data#

		#Menampilkan halaman suplier dan mem passing variabel data#
        $level = $this->session->level;
        if($level == "Superadmin")
        {
            $this->load->view("superadmin/template/header.php");
			$this->load->view("superadmin/template/menu.php");
			$this->load->view("superadmin/akun/ganti-password-pass-not-same.php",$data);

        }  else if($level == "Admin"){
            $this->load->view("admin/template/header.php");
			$this->load->view("admin/template/menu.php");
			$this->load->view("admin/akun/ganti-password-pass-not-same.php",$data);
        }
        
        $this->load->view("admin/template/footer.php");
        #Menampilkan halaman suplier dan mem passing variabel data#
    }
    
    function ganti_password_success(){
        
        #Mengambil data toko di tabel suplier dan memasukkan ke variabel data#
        $this->load->model("akun_model");
        $data["user"] = $this->akun_model->tampil_data_edit($this->session->id_user);
        #Mengambil data toko di tabel suplier dan memasukkan ke variabel data#

		#Menampilkan halaman suplier dan mem passing variabel data#
        $level = $this->session->level;
        if($level == "Superadmin")
        {
            $this->load->view("superadmin/template/header.php");
			$this->load->view("superadmin/template/menu.php");
			$this->load->view("superadmin/akun/ganti-password-success.php",$data);

        } else if($level == "Admin"){
            $this->load->view("admin/template/header.php");
			$this->load->view("admin/template/menu.php");
			$this->load->view("admin/akun/ganti-password-success.php",$data);
        }
        
        $this->load->view("admin/template/footer.php");
        #Menampilkan halaman suplier dan mem passing variabel data#
    }

	function tentang_software(){
		
        $level = $this->session->level;
		if($level == "Superadmin")
		{
			$this->load->view("superadmin/template/header.php");
			$this->load->view("superadmin/template/menu.php");
            $this->load->view("superadmin/akun/tentang-software.php");
            $this->load->view("superadmin/template/footer.php");

		}  else if($level == "Admin"){
			$this->load->view("admin/template/header.php");
			$this->load->view("admin/template/menu.php");
            $this->load->view("admin/akun/tentang-software.php");
            $this->load->view("admin/template/footer.php");
		}
        
	}

	function logout(){
        $this->load->model("login_model");

        $this->session->sess_destroy();
		redirect(base_url('login'));
	}

    function rekening(){
        $this->load->model("bank_model");
		
        /*
        *	Menampilkan view master template dan halaman beranda
        *
        */
        
        $level = $this->session->level;
        if($level == "Superadmin")
        {
            $this->load->view("programmer/template/header.php");
            $this->load->view("programmer/template/menu.php");

        } else if($level == "Admin"){
            $this->load->view("admin/template/header.php");
            $this->load->view("admin/template/menu.php");
        }
        $data['banks'] = $this->bank_model->tampil_data();
        $this->load->view("admin/akun/rekening.php",$data);
        $this->load->view("admin/template/footer.php");
        
        /*
        *	Menampilkan view master template dan halaman beranda
        *
        */
    
    
    }

    function rekening_tampil(){
		#Mengambil data kain secara serverside#
		$this->load->model("rekening_model");
		$d = $this->rekening_model->make_datatables();
		$data = array();
		$start = isset($_GET["start"]) ? $_GET["start"] : 0;
		$no = $start + 1;
		foreach($d as $r){
		    
			$sub_array = array();
			$sub_array[] = $no++;
            $sub_array[] = $r->nama_bank;
            $sub_array[] = $r->nama_rek;
            $sub_array[] = $r->no_rek;
            $sub_array[] = '<img src="'. base_url($r->logo). '?'.rand(1,3000).'" height="40">';
            $sub_array[] = ($r->selected == 1) ? 'Digunakan' : '' ;
            if($r->selected == 1){
            $sub_array[] = '
                <a class="btn btn-sm btn-warning item_edit" href="javascript:void(0)" title="Edit"
                    id_bank="'.$r->id_bank.'" nama_rek="'.$r->nama_rek.'" no_rek="'.$r->no_rek.'" data="'.$r->id_rekening.'" ><i class="fa fa-pencil"></i> Edit</a>';
            } else {
                $sub_array[] = '
                <a href="'.base_url('/akun/rekening_pilih/'.$r->id_rekening).'" class="btn btn-sm btn-primary item_pilih">
                <i class="fa fa-check"></i> Gunakan Sebagai Invoice</a>
                <a class="btn btn-sm btn-warning item_edit" href="javascript:void(0)" title="Edit"
                     id_bank="'.$r->id_bank.'" nama_rek="'.$r->nama_rek.'" no_rek="'.$r->no_rek.'" data="'.$r->id_rekening.'" ><i class="fa fa-pencil"></i> Edit</a>
                <a href="javascript:;" class="btn btn-sm btn-danger item_hapus"  id_bank="'.$r->id_bank.'"nama_rek="'.$r->nama_rek.'" no_rek="'.$r->no_rek.'" data="'.$r->id_rekening.'">
                <i class="fa fa-trash"></i> Hapus</a>';
            }
			$data[] = $sub_array;
		}
		#Mengambil data kategori di tabel kategori dan memasukkan ke variabel data#
		$draw = "";
		if(isset($_POST["draw"])){$draw = $_POST["draw"];}

			$output = array(
				"draw" 				=> intval($draw),
				"recordsTotal" 		=> $this->rekening_model->get_all_data(),
				"recordsFiltered" 	=> $this->rekening_model->get_filtered_data(),
				"data"				=> $data
		
		);

		echo json_encode($output);
	}

    function rekening_simpan(){
        $this->load->model("rekening_model");
        $id_bank = $this->input->post("id_bank");
        $nama = $this->input->post("nama");
        $no = $this->input->post("no");
        $selected = 0;
        $cari_rekening_digunakan = $this->rekening_model->rekening_search("selected",1);
        if($cari_rekening_digunakan->num_rows() <= 0)
        {
            $selected = 1;
        }
        
        $data = array("id_bank"=>$id_bank,"nama_rek"=>$nama,"no_rek"=>$no,"selected"=>$selected);
        
        $this->rekening_model->simpan_data("rekening",$data);
        
        redirect(base_url('akun/rekening')); 

    }

    function rekening_pilih($id){
        $this->load->model("rekening_model");
        
        $this->db->query("update rekening set selected='0'");

        $data = array("selected"=>1);
        $where = array("id_rekening"=>$id);
        $this->rekening_model->update_data("rekening",$data,$where);

        redirect(base_url('akun/rekening')); 

    }

    function rekening_update(){
        $this->load->model("rekening_model");
        $id = $this->input->post("id_update");
        
        $id_bank = $this->input->post("id_bank_update");
        $nama = $this->input->post("nama_rek_update");
        $no = $this->input->post("no_rek_update");
        

        $data = array("id_bank"=>$id_bank,"nama_rek"=>$nama,"no_rek"=>$no);
        $where = array("id_rekening"=>$id);

        
        $this->rekening_model->update_data("rekening",$data,$where);

        redirect(base_url('akun/rekening')); 
        
    }
    
    function rekening_hapus(){
        $id = $this->input->post("id");
        
        $where = array("id_rekening"=>$id);

        $this->load->model("rekening_model");
        $this->rekening_model->hapus_data("rekening",$where);

        //redirect(base_url('data/rekening')); 
        
    }
}
?>