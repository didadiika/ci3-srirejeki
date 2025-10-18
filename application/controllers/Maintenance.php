<?php
defined('BASEPATH') OR exit('No direct script access allowed');



class Maintenance extends CI_Controller{
	
	function __construct(){
		parent::__construct();
		$maintenance_status = "Off";
		$m = $this->db->query("select * from sistem_setting");
		if($m->num_rows() > 0)
		{
			foreach($m->result() as $r){
				$maintenance_status = $r->maintenance;
			}
		}

		if($maintenance_status == "Off")
		{
			$this->session->sess_destroy();
			redirect( base_url('login') );
		}
	}


	function index(){
        $this->load->view("pengunjung/maintenance");
	}


}


?>