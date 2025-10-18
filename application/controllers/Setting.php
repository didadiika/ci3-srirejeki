<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once (dirname(__FILE__) . "/BaseController.php");


class Setting extends BaseController{

	function __construct(){
		parent::__construct();

		$cek_login = $this->session->userdata('authenticated');
        if ($cek_login != true) {
			redirect( base_url('login') );
        }

	}

	
	function langganan(){
		
        	/*
			*	Menampilkan view master template dan halaman beranda
			*
			*/
            $level = $this->session->level;
            if($level == "Superadmin")
            {
				$this->load->view("superadmin/template/header.php");
                $this->load->view("superadmin/template/menu.php");
                $this->load->view("superadmin/setting/langganan.php");
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

    function langganan_tampil(){
		#Mengambil data kain secara serverside#
		$this->load->model("hosting_model");
		$kain = $this->hosting_model->make_datatables();
		$data = array();
		$start = isset($_POST["start"]) ? $_POST["start"] : 0;
		$no = $start + 1;
		foreach($kain as $r){
		    
			$sub_array = array();
			$sub_array[] = $no++;
            $sub_array[] = $r->provider;
            $sub_array[] = $r->email;
            $sub_array[] = tgl_pecah($r->masa_berlaku);
			$sub_array[] = '<a href="javascript:;" class="btn btn-xs btn-primary item_renew" data="'.$r->id_hosting.'">Perpanjang 1 Bulan</a>';
			$data[] = $sub_array;
		}
		#Mengambil data kategori di tabel kategori dan memasukkan ke variabel data#
		$draw = "";
		if(isset($_POST["draw"])){$draw = $_POST["draw"];}

			$output = array(
				"draw" 				=> intval($draw),
				"recordsTotal" 		=> $this->hosting_model->get_all_data(),
				"recordsFiltered" 	=> $this->hosting_model->get_filtered_data(),
				"data"				=> $data
		
		);

		echo json_encode($output);
	}

	function langganan_renew(){
        $id = $this->input->post('id');
        
        $q = $this->db->query("select * from hosting where id_hosting='$id' ");
        if($q->num_rows() > 0){
            foreach($q->result() as $r)
            {
                $masa_berlaku_awal = $r->masa_berlaku;
                $tahun = substr($r->masa_berlaku,0,4);
                $bulan = substr($r->masa_berlaku,5,2) + 1;
                $tanggal = substr($r->masa_berlaku,8,2);
                if($bulan > 12)
                {
                    $tahun = $tahun + 1;
                    $bulan = 1;
                }
                $masa_berlaku_baru = $tahun."-".$bulan."-".$tanggal;
            }

        $data = array("masa_berlaku"=>$masa_berlaku_baru);
        $where = array("id_hosting"=>$id);

        $this->load->model("hosting_model");
        $this->hosting_model->update_data("hosting",$data,$where);
        }
        

        redirect(base_url('setting/langganan')); 
        
    }

    function setting_backup(){
		
        /*
        *	Menampilkan view master template dan halaman beranda
        *
        */
        $level = $this->session->level;
        if($level == "Superadmin")
        {
            $this->load->view("superadmin/template/header.php");
            $this->load->view("superadmin/template/menu.php");
            $this->load->view("superadmin/setting/setting-backup.php");
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

    function setting_backup_tampil(){
    #Mengambil data kain secara serverside#
    $this->load->model("SettingBackup_model");
    $kain = $this->SettingBackup_model->make_datatables();
    $data = array();
    $start = isset($_POST["start"]) ? $_POST["start"] : 0;
    $no = $start + 1;
    foreach($kain as $r){
        
        $sub_array = array();
        $sub_array[] = $no++;
        $sub_array[] = $r->switch;
        $sub_array[] = $r->day_backup;
        if($r->switch == "On"){
            $sub_array[] = '<a href="javascript:;" class="btn btn-xs btn-danger item_off" data="'.$r->id.'">Matikan</a>';
        }
        else
        {
            $sub_array[] = '<a href="javascript:;" class="btn btn-xs btn-primary item_on" data="'.$r->id.'">Hidupkan</a>';
        }
        
        $data[] = $sub_array;
    }
    #Mengambil data kategori di tabel kategori dan memasukkan ke variabel data#
    $draw = "";
    if(isset($_POST["draw"])){$draw = $_POST["draw"];}

        $output = array(
            "draw" 				=> intval($draw),
            "recordsTotal" 		=> $this->SettingBackup_model->get_all_data(),
            "recordsFiltered" 	=> $this->SettingBackup_model->get_filtered_data(),
            "data"				=> $data
    
    );

    echo json_encode($output);
    }

    function setting_backup_on(){
        $id = $this->input->post('id');
        $data = array("switch"=>"On");
        $where = array("id"=>$id);

        $this->load->model("SettingBackup_model");
        $this->SettingBackup_model->update_data("backup_setting",$data,$where);
        
        

        redirect(base_url('setting/setting-backup')); 
        
    }

    function setting_backup_off(){
        $id = $this->input->post('id');
        $data = array("switch"=>"Off");
        $where = array("id"=>$id);

        $this->load->model("SettingBackup_model");
        $this->SettingBackup_model->update_data("backup_setting",$data,$where);
        
        

        redirect(base_url('setting/setting-backup')); 
        
    }

    function setting_maintenance(){
		
        /*
        *	Menampilkan view master template dan halaman beranda
        *
        */
        $level = $this->session->level;
        if($level == "Superadmin")
        {
            $this->load->view("superadmin/template/header.php");
            $this->load->view("superadmin/template/menu.php");
            $this->load->view("superadmin/setting/setting-maintenance.php");
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

    function setting_maintenance_tampil(){
    #Mengambil data kain secara serverside#
    $this->load->model("SistemSetting_model");
    $kain = $this->SistemSetting_model->make_datatables();
    $data = array();
    $start = isset($_POST["start"]) ? $_POST["start"] : 0;
    $no = $start + 1;
    foreach($kain as $r){
        
        $sub_array = array();
        $sub_array[] = $no++;
        $sub_array[] = $r->maintenance;
        if($r->maintenance == "On"){
            $sub_array[] = '<a href="javascript:;" class="btn btn-xs btn-primary item_off" data="'.$r->id.'">Masuk Mode Normal</a>';
        }
        else
        {
            $sub_array[] = '<a href="javascript:;" class="btn btn-xs btn-warning item_on" data="'.$r->id.'">Masuk Mode Maintenance</a>';
        }
        
        $data[] = $sub_array;
    }
    #Mengambil data kategori di tabel kategori dan memasukkan ke variabel data#
    $draw = "";
    if(isset($_POST["draw"])){$draw = $_POST["draw"];}

        $output = array(
            "draw" 				=> intval($draw),
            "recordsTotal" 		=> $this->SistemSetting_model->get_all_data(),
            "recordsFiltered" 	=> $this->SistemSetting_model->get_filtered_data(),
            "data"				=> $data
    
    );

    echo json_encode($output);
    }

    function setting_maintenance_on(){
        $id = $this->input->post('id');
        $data = array("maintenance"=>"On");
        $where = array("id"=>$id);

        $this->load->model("SistemSetting_model");
        $this->SistemSetting_model->update_data("sistem_setting",$data,$where);
        
        

        redirect(base_url('setting/setting-maintenance')); 
        
    }

    function setting_maintenance_off(){
        $id = $this->input->post('id');
        $data = array("maintenance"=>"Off");
        $where = array("id"=>$id);

        $this->load->model("SistemSetting_model");
        $this->SistemSetting_model->update_data("sistem_setting",$data,$where);
        
        

        redirect(base_url('setting/setting-maintenance')); 
        redirect(base_url('setting/setting-backup')); 
        
    }
}
?>