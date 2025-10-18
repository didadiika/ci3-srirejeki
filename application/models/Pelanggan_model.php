<?php
class Pelanggan_Model extends CI_Model{
    var $tabel = "pelanggan";
    var $relasi = array("deleted_at"=>NULL);
	var $pilih_kolom = array("*");
	var $order_kolom = array(null, "nama_pelanggan","alamat","telepon", NULL);
	    
    function tampil_data(){
      
        
        $this->db->select($this->pilih_kolom);
		$this->db->from($this->tabel);
		$this->db->where($this->relasi);

		if(isset($_GET["search"]["value"]))
		{
			$this->db->group_start();
			$this->db->like("nama_pelanggan", $_GET["search"]["value"]);
            $this->db->or_like("alamat", $_GET["search"]["value"]);
            $this->db->or_like("telepon", $_GET["search"]["value"]);
			$this->db->group_end();

		}

		if(isset($_GET["order"]))
		{
			$this->db->order_by($this->order_kolom[$_GET["order"]["0"]["column"]], $_GET["order"]["0"]["dir"]);
		}
		else
		{
			$this->db->order_by("nama_pelanggan", "ASC");
		}
    }
    
    function make_datatables(){
		$this->tampil_data();
		if(isset($_GET["length"]) && isset($_GET["start"]))
		{
		if($_GET["length"] != -1)
		{
			$this->db->limit($_GET["length"], $_GET["start"]);
		}
		}
		$query = $this->db->get();

		return $query->result();
	}

	function get_filtered_data(){
		$this->tampil_data();
		$query = $this->db->get();

		return $query->num_rows();
	}

	function get_all_data(){
		$this->db->select("*");
		$this->db->from($this->tabel);
        $this->db->where($this->relasi);


		return $this->db->count_all_results();
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


	function pelanggan_all(){
        $this->db->select("*");
        $this->db->from("pelanggan");
        $this->db->where(array("deleted_at"=>NULL));
		$this->db->order_by("pelanggan","ASC");
        
        $hasil = $this->db->get();
        return $hasil;
    }
}


?>