<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once (dirname(__FILE__) . "/BaseController.php");


class Backup extends BaseController{

	function __construct(){
		parent::__construct();
        
        $this->load->model("backup_model");
        

	}


	function index(){
        $status = "Off";
        $q = $this->db->query("select * from backup_setting order by id desc limit 1");
        if($q->num_rows() > 0)
        {
            foreach($q->result() as $r){
                $status = $r->switch;
                $hari_backup = $r->day_backup;
            }
        }



        if($status == "On")
        {
        #Periksa backup hari ini
        $tanggal_hari_ini = date("Y-m-d");
        $data = $this->backup_model->tampil_data($tanggal_hari_ini);
        $jumlah = $data->num_rows();

        if($jumlah <= 1){
        #Membuat backup pertama dan kedua pada hari ini#
        $this->load->dbutil();
		$this->load->helper('file');
		
		$config = array(
			'format'	=> 'zip',
			'filename'	=> 'database.sql'
		);
		
		$backup = $this->dbutil->backup($config);
        $folder = date("Y-m-d");
        $folder_penuh = FCPATH.'backup-auto-db/'.$folder;
        if(!is_dir($folder_penuh))
        {
            mkdir($folder_penuh, 0755, true);
        }
		$save = $folder_penuh.'/backup-'.date("ymdHis").'-db.zip';
		
        write_file($save, $backup);
        #simpan ke database
        $data = array("id_backup"=>id_primary(),
        "date_time"=>date("Y-m-d H:i:s"),
        "folder"=>$folder_penuh,
        "nama_file"=>substr($save,strlen($folder_penuh)+1),
        "link_file"=>$save);
        $this->backup_model->simpan_data($data,"backup_db");
       
        }
        else if($jumlah > 1)
        {
        #Update berkala data backup kedua pada hari ini#
        $d = $this->backup_model->tampil_data_hari(date("Y-m-d"));
        if($d->num_rows() > 0 )
        {
            $counter = 0;
            foreach($d->result() as $r){
                if($counter > 0)
                {
                    $nama_file = $r->link_file;
                    $id_backup = $r->id_backup;
                    unlink($nama_file);
                    $where = array("id_backup"=>$id_backup);
                    $tabel = "backup_db";
                    $this->backup_model->hapus_data($where,$tabel);
                }
                $counter++;
            }
            $this->load->dbutil();
		$this->load->helper('file');
		
		$config = array(
			'format'	=> 'zip',
			'filename'	=> 'database.sql'
		);
		
		$backup = $this->dbutil->backup($config);
		$folder = date("Y-m-d");
        $folder_penuh = FCPATH.'backup-auto-db/'.$folder;
        if(!is_dir($folder_penuh))
        {
            mkdir($folder_penuh, 0755, true);
        }
		$save = $folder_penuh.'/backup-'.date("ymdHis").'-db.zip';
		
        write_file($save, $backup);
        #simpan ke database
        $data = array("id_backup"=>id_primary(),
        "date_time"=>date("Y-m-d H:i:s"),
        "folder"=>$folder_penuh,
        "nama_file"=>substr($save,strlen($folder_penuh)+1),
        "link_file"=>$save);
        $this->backup_model->simpan_data($data,"backup_db");
        }
        }
        #hapus data backup lebih dari batas hari
        $hari_backup = $hari_backup * 24 * 60 * 60;
        $batas = time() - $hari_backup;
        $batas = date("Y-m-d H:i:s",$batas);
        
        $d = $this->backup_model->tampil_data_agregat($batas);
        if($d->num_rows() > 0 )
        {
            foreach($d->result() as $r){
                $nama_file = $r->link_file;
                $id_backup = $r->id_backup;
                unlink($nama_file);
                $periksa_file_dalam_folder = (count(glob("$r->folder/*")) === 0) ? 'Empty' : 'Not empty';
                if($periksa_file_dalam_folder == "Empty")
                {
                    rmdir($r->folder);
                }
                $where = array("id_backup"=>$id_backup);
                $tabel = "backup_db";
                $this->backup_model->hapus_data($where,$tabel);
            }
        }
    }

		
	}


}
?>