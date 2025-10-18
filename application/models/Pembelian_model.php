<?php
class Pembelian_Model extends CI_Model{
    var $tabel = "pembelian";
    var $where = "pembelian.deleted_at is NULL";
	var $pilih_kolom = "*";
	var $order_kolom = array(NULL,"pengirim.nama_pengirim","pembelian.no_polisi","pembelian.tanggal","pembelian.total_tonase",
    "pembelian.harga_satuan","pembelian.harga_kbk","pembelian.status", NULL);
	    
    function tampil_data(){
      
        
        $this->db->select($this->pilih_kolom);
		$this->db->from($this->tabel);
        $this->db->where($this->where);
        $this->db->join('pengirim','pengirim.id_pengirim = pembelian.id_pengirim','inner');

		if(isset($_GET["search"]["value"]))
		{
			$this->db->group_start();
			$this->db->like("pengirim.nama_pengirim", $_GET["search"]["value"]);
            $this->db->or_like("pembelian.no_polisi", $_GET["search"]["value"]);
            $this->db->or_like("pembelian.tanggal", substr($_GET["search"]["value"],6,4)."-".substr($_GET["search"]["value"],3,2)."-".substr($_GET["search"]["value"],0,2));
            $this->db->or_like("pembelian.total_tonase", $_GET["search"]["value"]);
            $this->db->or_like("pembelian.harga_satuan", $_GET["search"]["value"]);
            $this->db->or_like("pembelian.harga_kbk", $_GET["search"]["value"]);
            $this->db->or_like("pembelian.status", $_GET["search"]["value"]);
			$this->db->group_end();

		}

		if(isset($_GET["order"]))
		{
			$this->db->order_by($this->order_kolom[$_GET["order"]["0"]["column"]], $_GET["order"]["0"]["dir"]);
		}
		else
		{
			$this->db->order_by("pembelian.tanggal", "DESC");
            $this->db->order_by("pembelian.created_at", "DESC");
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
        $this->db->where($this->where);
        $this->db->join('pengirim','pengirim.id_pengirim = pembelian.id_pengirim','inner');


		return $this->db->count_all_results();
	}
	
    

    function simpan_data($tabel,$data){
        $this->db->insert($tabel,$data);
        $insert_id = $this->db->insert_id();
        return  $insert_id;
    }

    function tampil_data_edit($id){
        $this->db->select("*");
        $this->db->from("pembelian");
        $this->db->where("pembelian.id_pembelian = '$id'");
        
        $hasil = $this->db->get();
        return $hasil;
    }

    function update_data($tabel,$data,$where){
        $this->db->where($where);
        $this->db->update($tabel,$data);
    }

	function hapus_data($tabel,$where){
        $this->db->where($where);
        $this->db->delete($tabel);
    }

	function tampil_keranjang($id){
        $this->db->select("*");
        $this->db->from("pembelian_d");
        $this->db->where("id_pembelian = '$id'");
        
        $hasil = $this->db->get();
        return $hasil;
    }

    function cari_total($id){
        $hasil = $this->db->query("select sum(sub_total) as total from pembelian_d where id_pembelian='$id' ");
        
        return $hasil;

    }

    
}


?>