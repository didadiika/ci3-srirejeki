<?php
class Backup_model extends CI_Model{

	

	function tampil_data($tgl){
		$this->db->select("*");
		$this->db->from("backup_db");
		$this->db->where("DATE(date_time)",$tgl);

		$hasil = $this->db->get();
		

		return $hasil;
	}


	function tampil_data_agregat($a){
		$this->db->select("*");
		$this->db->from("backup_db");
		$this->db->where("date_time < '$a' ");

		$hasil = $this->db->get();
		

		return $hasil;
	}

	function tampil_data_hari($date){
		$this->db->select("*");
		$this->db->from("backup_db");
		$this->db->where("DATE(date_time) = '$date' ");
		$this->db->order_by("date_time","ASC");

		$hasil = $this->db->get();
		

		return $hasil;
	}



	function simpan_data($data,$tabel){

		$this->db->insert($tabel,$data);
	}



	function update_data($where,$data,$tabel){
		$this->db->where($where);
		$this->db->update($tabel,$data);
	}

	function hapus_data($where,$tabel){
		$this->db->where($where);
		$this->db->delete($tabel);

	}
}

?>