<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once (dirname(__FILE__) . "/BaseController.php");


class Login extends BaseController{
	
	function __construct(){
		parent::__construct();
		$this->load->model("login_model");

		
	}


	function index(){
		$maintenance_status = "Off";
		$m = $this->db->query("select * from sistem_setting");
		if($m->num_rows() > 0)
		{
			foreach($m->result() as $r){
				$maintenance_status = $r->maintenance;
			}
		}

		if($maintenance_status == "On")
		{
			$this->session->sess_destroy();
			redirect( base_url('maintenance') );
		}

		
		$cek_login = $this->session->userdata('authenticated');
        if ($cek_login == true) {
			redirect( base_url('home') );
        }
        else
        {
			
			$data = array("masa_berlaku"=>$this->masa_berlaku);
        	$this->load->view("pengunjung/login",$data);
        }
		
	}

	function login_super(){
		$cek_login = $this->session->userdata('authenticated');
        if ($cek_login == true) {
			redirect( base_url('home') );
        }
        else
        {
			$this->load->view("pengunjung/login-super");
        }
		
	}


	function aksi_login(){
		$username = $this->input->post('username');
		$password = $this->input->post('password');


		$data_login = $this->login_model->cek_login("user",$username);
		if(empty($data_login))
		{
			// Buat session flashdata
			$this->session->set_flashdata('message', 'Username tidak ditemukan'); 

      		// Redirect ke halaman login
      		redirect(base_url('login')); 
		}
		else
		{
			if(password_verify($password,$data_login->password))
			{
				
				$session = array(
					'authenticated'=>true, // Buat session authenticated dengan value true
				  	'id_user'=>$data_login->id_user,  
				  	'username'=>$data_login->username,  // Buat session username
					'level'=>$data_login->level // Buat session authenticated
				);

				$this->session->set_userdata($session);
        		redirect(base_url('home')); 
			}
			else
			{
				$this->session->set_flashdata('message', 'Password salah!'); 
				redirect(base_url('login')); 
			}
		}

	}


	



}


?>