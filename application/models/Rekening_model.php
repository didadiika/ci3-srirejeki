<?php
class Rekening_Model extends CI_Model{
    var $tabel = "rekening";
	var $pilih_kolom = array("*");
	var $order_kolom = array(null, "rekening_bank.nama_bank","rekening.nama_rek","rekening.no_rek",NULL, NULL);
	
	    
    function tampil_data(){
      
        
        $this->db->select($this->pilih_kolom);
		$this->db->from($this->tabel);
		$this->db->join('rekening_bank','rekening_bank.id_bank = rekening.id_bank','inner');

		if(isset($_GET["search"]["value"]))
		{
			$this->db->group_start();
			$this->db->like("rekening_bank.nama_bank", $_GET["search"]["value"]);
            $this->db->or_like("rekening.nama_rek", $_GET["search"]["value"]);
            $this->db->or_like("rekening.no_rek", $_GET["search"]["value"]);
			$this->db->group_end();

		}

		if(isset($_GET["order"]))
		{
			$this->db->order_by($this->order_kolom[$_GET["order"]["0"]["column"]], $_GET["order"]["0"]["dir"]);
		}
		else
		{
			$this->db->order_by("rekening_bank.nama_bank", "ASC");
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


	function rekening_search($field,$data){
        $this->db->select("*");
        $this->db->from("rekening");
        $this->db->where(array($field=>$data));
        
        $hasil = $this->db->get();
        return $hasil;
    }

	function hapus_data($tabel,$where){
        $this->db->where($where);
        $this->db->delete($tabel);
    }
}


?>