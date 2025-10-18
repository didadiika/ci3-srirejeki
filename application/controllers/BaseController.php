<?php
defined('BASEPATH') OR exit('No direct script access allowed');



class BaseController extends CI_Controller{
	
	function __construct(){
		parent::__construct();
		$this->load->model("login_model");


        $hosting = $this->login_model->periksa_hosting();
        if($hosting->num_rows() > 0)
        {
            foreach($hosting->result() as $r){
                $this->masa_berlaku = $r->masa_berlaku;
            }
        }
        else
        {
            $this->masa_berlaku = NULL;
        }

		$sekarang = time();
        $masa_berlaku = strtotime($this->masa_berlaku);
        $sisa = $masa_berlaku - $sekarang;
        $hari = ceil($sisa / (24*60*60));
        if($hari <= 0){
            $this->session->sess_destroy();
			
        }
	}

    public function index(){

        
    }
}


?>