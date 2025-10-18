<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once (dirname(__FILE__) . "/BaseController.php");

class Home extends BaseController{

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

	
}
?>