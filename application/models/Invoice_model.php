<?php
class Invoice_Model extends CI_Model{
    var $tabel = "invoice";
    var $where = "invoice.deleted_at is NULL";
	var $pilih_kolom = "*";
	var $order_kolom = array(NULL,"invoice.tanggal","pelanggan.nama_pelanggan","invoice.harga_kbk","invoice.total","invoice.status","invoice.status_bayar", NULL, NULL);
	    
    function tampil_data(){
      
        
        $this->db->select($this->pilih_kolom);
		$this->db->from($this->tabel);
        $this->db->where($this->where);
        $this->db->join('pelanggan','pelanggan.id_pelanggan = invoice.id_pelanggan','inner');

		if(isset($_GET["search"]["value"]))
		{
			$this->db->group_start();
			$this->db->like("pelanggan.nama_pelanggan", $_GET["search"]["value"]);
            $this->db->or_like("invoice.harga_kbk", $_GET["search"]["value"]);
            $this->db->or_like("invoice.tanggal", substr($_GET["search"]["value"],6,4)."-".substr($_GET["search"]["value"],3,2)."-".substr($_GET["search"]["value"],0,2));
            $this->db->or_like("invoice.total", $_GET["search"]["value"]);
            $this->db->or_like("invoice.status", $_GET["search"]["value"]);
			$this->db->group_end();

		}

		if(isset($_GET["order"]))
		{
			$this->db->order_by($this->order_kolom[$_GET["order"]["0"]["column"]], $_GET["order"]["0"]["dir"]);
		}
		else
		{
			$this->db->order_by("invoice.tanggal", "DESC");
            $this->db->order_by("invoice.created_at", "DESC");
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
        $this->db->join('pelanggan','pelanggan.id_pelanggan = invoice.id_pelanggan','inner');


		return $this->db->count_all_results();
	}
	
    

    function simpan_data($tabel,$data){
        $this->db->insert($tabel,$data);
        $insert_id = $this->db->insert_id();
        return  $insert_id;
    }

    function tampil_data_edit($id){
        $this->db->select("*");
        $this->db->from("invoice");
        $this->db->where("invoice.id_invoice = '$id'");
        
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
        $this->db->from("invoice_d");
        $this->db->where("id_invoice = '$id'");
        
        $hasil = $this->db->get();
        return $hasil;
    }

    function cari_total($id){
        $hasil = $this->db->query("select sum(sub_total) as total from invoice_d where id_invoice='$id' ");
        
        return $hasil;

    }

    
}


?>