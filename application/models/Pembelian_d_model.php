<?php
class Pembelian_d_Model extends CI_Model{
   

    function simpan_data($tabel,$data){
        $this->db->insert($tabel,$data);
        $insert_id = $this->db->insert_id();
        return  $insert_id;
    }

    function update_data($tabel,$data,$where){
        $this->db->where($where);
        $this->db->update($tabel,$data);
    }

	function hapus_data($tabel,$where){
        $this->db->where($where);
        $this->db->delete($tabel);
    }

}


?>