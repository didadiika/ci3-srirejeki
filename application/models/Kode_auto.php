<?php
/*
*	-- Class kode otomatis --
*
*	Penulis : Ayatullah Wahyu Handika
*	Startup	: Paus Menari Developer
*	Tanggal	: 2 November 2019
*	Telepon	: 08574099355
*	Email 	: didadiika@gmail.com
*
*/


class Kode_auto extends CI_Model{
	


	function kd_angka($nama_tabel,$nama_kolom)
	{
		$this->db->select("$nama_kolom");
		$this->db->from("$nama_tabel");
		$this->db->order_by("$nama_kolom","desc");
		$this->db->limit("1");
		$data = $this->db->get();
		$hasil = $data->row();
		$jumlah_data = $data->num_rows();

		if($jumlah_data > 0)
		{
			$kode = $hasil->$nama_kolom;
			$kode = intval($kode) + 1;
		}
		else
		{
			$kode = 1;
		}

		return $kode;


	}

	function kd_angka_perulangan($nama_tabel,$nama_kolom)
	{
		$this->db->select("$nama_kolom");
		$this->db->from("$nama_tabel");
		$this->db->order_by("$nama_kolom","desc");
		$this->db->limit("1");
		$data = $this->db->get();
		$hasil = $data->row();
		$jumlah_data = $data->num_rows();

		$kolom = $this->db->field_data("$nama_tabel");
    	foreach ($kolom as $kol) {
    		if($kol->name == $nama_kolom)
    		{
    			$panjang_kolom = $kol->max_length;
    		}
    	}

		if($jumlah_data > 0){

    		$kode_terakhir = ltrim($hasil->$nama_kolom, '0');
    		$kode_baru = intval($kode_terakhir) + 1;
    		$panjang_kode_baru = strlen($kode_baru);
    		$panjang_perulangan = $panjang_kolom-($panjang_kode_baru);
    		
    		$tmp = "";
			for($i=0; $i < $panjang_perulangan; $i++)
			{
				$tmp = $tmp."0";
			}

    		$kode = $tmp.$kode_baru;
    	}
    	else
    	{
    		$kode_baru = 1;
    		$panjang_kode_baru = strlen($kode_baru);
    		$panjang_perulangan = $panjang_kolom-($panjang_kode_baru);
    		
    		$tmp = "";
			for($i=0; $i < $panjang_perulangan; $i++)
			{
				$tmp = $tmp."0";
			}
    		$kode = $tmp.$kode_baru;
		}
		return $kode;


	}


	function kd_angka_inisial($nama_tabel, $nama_kolom, $inisial){

		$this->db->select("$nama_kolom");
		$this->db->from("$nama_tabel");
		$this->db->where("$nama_kolom like '$inisial%'");
		$this->db->order_by("$nama_kolom","desc");
		$this->db->limit("1");
		$data = $this->db->get();
		$jumlah_data = $data->num_rows();
		$hasil = $data->row();

		$panjang_inisial = strlen($inisial);

		$kolom = $this->db->field_data("$nama_tabel");
    	foreach ($kolom as $kol) {
    		if($kol->name == $nama_kolom)
    		{
    			$panjang_kolom = $kol->max_length;
    		}
    	}

    	if($jumlah_data > 0){

    		$kode_terakhir = $hasil->$nama_kolom;
    		$kode_terakhir = substr($kode_terakhir,$panjang_inisial,$panjang_kolom-$panjang_inisial);
    		$kode_baru = intval($kode_terakhir) + 1;
    		$panjang_kode_baru = strlen($kode_baru);
    		$panjang_perulangan = $panjang_kolom-($panjang_inisial+$panjang_kode_baru);
    		
    		$tmp = "";
			for($i=0; $i < $panjang_perulangan; $i++)
			{
				$tmp = $tmp."0";
			}

    		$kode = $inisial.$tmp.$kode_baru;
    	}
    	else
    	{
    		$kode_baru = 1;
    		$panjang_kode_baru = strlen($kode_baru);
    		$panjang_perulangan = $panjang_kolom-($panjang_inisial+$panjang_kode_baru);
    		
    		$tmp = "";
			for($i=0; $i < $panjang_perulangan; $i++)
			{
				$tmp = $tmp."0";
			}
    		$kode = $inisial.$tmp.$kode_baru;
    	}
    		return $kode;
    	
	}

}
?>