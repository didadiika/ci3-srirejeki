<?php
class Akun_Model extends CI_Model{

	    
    function tampil_data(){
        $this->db->select("*");
        $this->db->from("user");
        $this->db->where("level != 'Programmer'");
        
        $hasil = $this->db->get();
        return $hasil;
    }

    function simpan_data($tabel,$data){
        $this->db->insert($tabel,$data);
        $insert_id = $this->db->insert_id();
        return  $insert_id;
    }

    function tampil_data_edit($id_user){
        $this->db->select("*");
        $this->db->from("user");
        $this->db->where("id_user",$id_user);
        
        $hasil = $this->db->get();
        return $hasil;
    }

    function update_data($tabel,$data,$where){
        $this->db->where($where);
        $this->db->update($tabel,$data);
    }

    function hapus_data($where, $tabel){
        $this->db->where($where);
        $this->db->delete($tabel);
    }
}


?>