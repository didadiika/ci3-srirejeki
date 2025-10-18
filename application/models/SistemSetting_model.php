<?php
class SistemSetting_Model extends CI_Model{
    var $tabel = "sistem_setting";
	var $pilih_kolom = array("*");
	var $order_kolom = array(null, "maintenance", NULL);
	    
    function tampil_data(){
      
        
        $this->db->select($this->pilih_kolom);
		$this->db->from($this->tabel);

		if(isset($_POST["search"]["value"]))
		{
			$this->db->group_start();
			$this->db->like("maintenance", $_POST["search"]["value"]);
			$this->db->group_end();

		}

		if(isset($_POST["order"]))
		{
			$this->db->order_by($this->order_kolom[$_POST["order"]["0"]["column"]], $_POST["order"]["0"]["dir"]);
		}
		else
		{
			$this->db->order_by("id", "ASC");
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

		return $this->db->count_all_results();
	}
	
    

    function simpan_data($tabel,$data){
        $this->db->insert($tabel,$data);
        $insert_id = $this->db->insert_id();
        return  $insert_id;
    }

    function tampil_data_edit($id_kain){
        $this->db->select("*");
        $this->db->from("sistem_setting");
        $this->db->where("sistem_setting",$id_kain);
        
        $hasil = $this->db->get();
        return $hasil;
    }

    function update_data($tabel,$data,$where){
        $this->db->where($where);
        $this->db->update($tabel,$data);
    }
}


?>