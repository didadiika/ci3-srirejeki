<?php
class Login_Model extends CI_Model{

	function cek_login($tabel,$username){
			
			$this->db->where(array('username'=>$username));
			$hasil = $this->db->get($tabel)->row();

			return $hasil;
	}

	function periksa_hosting(){
		$hasil = $this->db->query("select * from hosting");
		return $hasil;
	}

	function simpan_data($tabel,$data){
        $this->db->insert($tabel,$data);
        $insert_id = $this->db->insert_id();
        return  $insert_id;
    }

	function update_data($tabel,$data,$where){
        $this->db->where($where);
        $this->db->update($tabel,$data);
    }
}


?>