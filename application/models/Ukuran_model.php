<?php
class Ukuran_Model extends CI_Model{
    var $tabel = "ukuran";
    var $relasi = array("deleted_at"=>NULL);
	var $pilih_kolom = array("*");
	var $order_kolom = array(null, "ukuran", NULL);
	    
    function tampil_data(){
      
        
        $this->db->select($this->pilih_kolom);
		$this->db->from($this->tabel);
		$this->db->where($this->relasi);

		if(isset($_POST["search"]["value"]))
		{
			$this->db->group_start();
			$this->db->like("ukuran", $_POST["search"]["value"]);
			$this->db->group_end();

		}

		if(isset($_POST["order"]))
		{
			$this->db->order_by($this->order_kolom[$_POST["order"]["0"]["column"]], $_POST["order"]["0"]["dir"]);
		}
		else
		{
			$this->db->order_by("ukuran", "ASC");
		}
    }
    
    function make_datatables(){
		$this->tampil_data();
		if(isset($_POST["length"]) && isset($_POST["start"]))
		{
		if($_POST["length"] != -1)
		{
			$this->db->limit($_POST["length"], $_POST["start"]);
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

    function tampil_data_edit($id){
        $this->db->select("*");
        $this->db->from("ukuran");
        $this->db->where("id_ukuran",$id);
        
        $hasil = $this->db->get();
        return $hasil;
    }

    function update_data($tabel,$data,$where){
        $this->db->where($where);
        $this->db->update($tabel,$data);
    }


	function ukuran_all(){
        $this->db->select("*");
        $this->db->from("ukuran");
        $this->db->where(array("deleted_at"=>NULL));
		$this->db->order_by("ukuran","ASC");
        
        $hasil = $this->db->get();
        return $hasil;
    }
}


?>