<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Mike42\Escpos\ImagickEscposImage;#Butuh Ekstensi Imagick
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;

include_once (dirname(__FILE__) . "/BaseController.php");

class Payroll extends BaseController{

	function __construct(){
		parent::__construct();

		$cek_login = $this->session->userdata('authenticated');
        if ($cek_login != true) {
			redirect( base_url('login') );
        }

	}

	
	function index(){
		
        	/*
			*	Menampilkan view master template dan halaman beranda
			*
			*/
            $level = $this->session->level;
            if($level == "Programmer")
            {
				$this->load->view("programmer/template/header.php");
                $this->load->view("programmer/template/menu.php");

            } else if($level == "Owner"){
				$this->load->view("owner/template/header.php");
                $this->load->view("owner/template/menu.php");
            } else if($level == "Admin"){
				$this->load->view("admin/template/header.php");
                $this->load->view("admin/template/menu.php");
            }
        	$this->load->view("admin/beranda.php");
        	$this->load->view("admin/template/footer.php");
        	/*
			*	Menampilkan view master template dan halaman beranda
			*
			*/
    }


    function bayar(){
        
        #Mengambil data toko di tabel suplier dan memasukkan ke variabel data#
        $this->load->model("jabatan_model");
        
        #Mengambil data toko di tabel suplier dan memasukkan ke variabel data#

		#Menampilkan halaman suplier dan mem passing variabel data#
        $level = $this->session->level;
        if($level == "Programmer")
        {
            $this->load->view("programmer/template/header.php");
            $this->load->view("programmer/template/menu.php");

        } else if($level == "Owner"){
            $this->load->view("owner/template/header.php");
            $this->load->view("owner/template/menu.php");
        } else if($level == "Admin"){
            $this->load->view("admin/template/header.php");
            $this->load->view("admin/template/menu.php");
        }
        $this->load->view("admin/bayar-gaji.php");
        $this->load->view("admin/template/footer.php");
        #Menampilkan halaman suplier dan mem passing variabel data#
    }
    
    function slip_tampil(){
		#Mengambil data kain secara serverside#
		$this->load->model("slipgaji_model");
		$kain = $this->slipgaji_model->make_datatables();
		$data = array();
		$start = isset($_POST["start"]) ? $_POST["start"] : 0;
		$no = $start + 1;
		foreach($kain as $r){
		    
			$sub_array = array();
			$sub_array[] = $no++;
            $sub_array[] = $r->no_slip;
            $sub_array[] = $r->tahun." - ".$r->bulan;
            $sub_array[] = $r->minggu_ke;
            $sub_array[] = $r->nama_pegawai;
            $sub_array[] = uang($r->total);
            
            if($r->status == "Proses")
            {
                $sub_array[] = '<span class="badge btn-warning">Proses</span>';
                $sub_array[] = '<a class="btn btn-xs btn-warning" href="'. base_url('bayar-gaji/input/'.$this->enkripsi_url->encode($r->id_gaji_slip)).'" title="Edit">Edit</a>
            <a href="javascript:;" class="btn btn-xs btn-danger item_hapus" data="'.$this->enkripsi_url->encode($r->id_gaji_slip).'" 
            tahun="'.$r->tahun.'" bulan="'.$r->bulan.'" minggu="'.$r->minggu_ke.'">Hapus</a>';
            }
            else if($r->status == "Simpan")
            {
                $sub_array[] = '<span class="badge btn-success">Simpan</span>';
                $sub_array[] = '<a class="btn btn-xs btn-warning" href="'. base_url('bayar-gaji/input/'.$this->enkripsi_url->encode($r->id_gaji_slip)).'" title="Edit">Edit</a>
                <a href="javascript:;" class="btn btn-xs btn-info item_selesai" data-selesai="'.$this->enkripsi_url->encode($r->id_gaji_slip).'" 
                tahun="'.$r->tahun.'" bulan="'.$r->bulan.'" minggu="'.$r->minggu_ke.'">Selesai</a>
                <a href="javascript:;" class="btn btn-xs btn-danger item_hapus" data="'.$this->enkripsi_url->encode($r->id_gaji_slip).'" 
            tahun="'.$r->tahun.'" bulan="'.$r->bulan.'" minggu="'.$r->minggu_ke.'">Hapus</a>';
            }
            else
            {
                $link_cetak_struk = base_url('bayar-gaji/struk/'.$this->enkripsi_url->encode($r->id_gaji_slip));#dimatikan untuk hosting tdk support printer local
                $sub_array[] = '<span class="badge btn-primary">Selesai</span>';
                $sub_array[] = '<a class="btn btn-xs btn-success" target="_blank" href="'. base_url('bayar-gaji/cetak/'.$this->enkripsi_url->encode($r->id_gaji_slip)).'" title="Cetak">Cetak</a>
                <a class="btn btn-xs btn-primary" target="_blank" href="'.$link_cetak_struk.'" title="Cetak">Struk</a>
                
            <a href="javascript:;" class="btn btn-xs btn-danger item_hapus" data="'.$this->enkripsi_url->encode($r->id_gaji_slip).'"
            tahun="'.$r->tahun.'" bulan="'.$r->bulan.'" minggu="'.$r->minggu_ke.'">Hapus</a>';
            }
			
			$data[] = $sub_array;
		}
		#Mengambil data kategori di tabel kategori dan memasukkan ke variabel data#
		$draw = "";
		if(isset($_POST["draw"])){$draw = $_POST["draw"];}

			$output = array(
				"draw" 				=> intval($draw),
				"recordsTotal" 		=> $this->slipgaji_model->get_all_data(),
				"recordsFiltered" 	=> $this->slipgaji_model->get_filtered_data(),
				"data"				=> $data
		
		);

		echo json_encode($output);
	}

    function pegawai_detail_tampil(){
		#Mengambil data kain secara serverside#
        $id = $this->input->post("id");
		$this->load->model("pegawai_model");
		$pegawai = $this->pegawai_model->tampil_data_edit($id);
		if($pegawai->num_rows() > 0)
        {
            foreach($pegawai->result() as $r){
                $jabatan = $r->jabatan;
                $alamat = $r->alamat;
            }
            $output = array("jabatan"=>$jabatan,"alamat"=>$alamat);
            
        }
        else
        {
            $output = array("jabatan"=>"","alamat"=>"");
        }
        echo json_encode($output);
		
	}

    function pegawai_cek_slip_ganda(){
		#Mengambil data kain secara serverside#
        $id = $this->input->post("id");
        $tahun = $this->input->post("tahun");
        $bulan = $this->input->post("bulan");
        $minggu = $this->input->post("minggu");

		$this->load->model("slipgaji_model");
		$slip = $this->slipgaji_model->cek_slip_ganda($id,$tahun,$bulan,$minggu);
		echo $slip->num_rows();
		
	}

    function bayar_input(){
        

        #Menampilkan halaman suplier dan mem passing variabel data#
        $level = $this->session->level;
        if($level == "Programmer")
        {
            $this->load->view("programmer/template/header.php");
            $this->load->view("programmer/template/menu.php");

        } else if($level == "Owner"){
            $this->load->view("owner/template/header.php");
            $this->load->view("owner/template/menu.php");
        } else if($level == "Admin"){
            $this->load->view("admin/template/header.php");
            $this->load->view("admin/template/menu.php");
        }

        
        if($this->enkripsi_url->decode($this->uri->segment(3)))
        {
        $id = $this->enkripsi_url->decode($this->uri->segment(3));
        $this->load->model("slipgaji_model");
        $data["slip"] = $this->slipgaji_model->tampil_data_edit($id);

        $this->load->model("gaji_model");
        $data["gaji"] = $this->gaji_model->tampil_semua_gaji();

        $data["list"] = $this->slipgaji_model->tampil_list_gaji_slip($id);
        $this->load->view("admin/bayar-gaji-inputing.php",$data);
        }
        else
        {
        
        $this->load->model("pegawai_model");
        $data["pegawai"] = $this->pegawai_model->tampil_pegawai();
        $this->load->view("admin/bayar-gaji-input.php",$data);
        }
        $this->load->view("admin/template/footer.php");
        #Menampilkan halaman suplier dan mem passing variabel data#
    }


    function bayar_buat(){

        $tanggal = tgl_pecah($this->input->post("tanggal"));
        $tahun = $this->input->post("tahun");
        $bulan = $this->input->post("bulan");
        $minggu = $this->input->post("minggu_ke");
        $id_pegawai = $this->input->post("id_pegawai");
        $keterangan = $this->input->post("keterangan");
        $id = id_primary();
        $data = array("id_gaji_slip"=>$id,"id_pegawai"=>$id_pegawai,"tanggal"=>$tanggal,"tahun"=>$tahun,"bulan"=>$bulan,"minggu_ke"=>$minggu,
    "keterangan"=>$keterangan,"status"=>"Proses");

        $this->load->model("slipgaji_model");
        $this->slipgaji_model->simpan_data("gaji_slip",$data);

        redirect(base_url('bayar-gaji/input/'.$this->enkripsi_url->encode($id)));
        
    }

    function bayar_hapus(){
        $id = $this->enkripsi_url->decode($this->input->post('id_hapus'));
        
        $data = array("deleted_at"=>date("Y-m-d H:i:s"));
        $where = array("id_gaji_slip"=>$id);

        $this->load->model("slipgaji_model");
        $this->slipgaji_model->update_data("gaji_slip",$data,$where);
        

        redirect(base_url('bayar-gaji')); 
        
    }

    function bayar_simpan(){
        $id = $this->enkripsi_url->decode($this->input->post("id"));
        $this->load->model("kode_auto");
        $no_slip = $this->kode_auto->kd_angka_inisial("gaji_slip","no_slip",date("ymd"));

        $data = array("no_slip"=>$no_slip,"status"=>"Simpan","updated_at"=>date("Y-m-d H:i:s"));
        $where = array("id_gaji_slip"=>$id);
        $this->load->model("slipgaji_model");
        $this->slipgaji_model->update_data("gaji_slip",$data,$where);
        
        redirect(base_url('bayar-gaji')); 

    }


    function tampil_gaji_harian_pegawai(){
        $id_pegawai = $this->input->post("id_pegawai");

        $this->load->model("gaji_model");
        $g = $this->gaji_model->tampil_gaji_harian_jabatan($id_pegawai);
        if($g->num_rows() > 0)
        {
            foreach($g->result() as $r){
                echo uang($r->nominal);
            }
        }
        else
        {
            echo 0;
        }

    }

    function bayar_tambah(){
        $id_dgs = id_primary();
        $id_gaji_slip = $this->input->post("id_gaji_slip");
        $id_gaji = $this->input->post("id_gaji");
        $jumlah = $this->input->post("jumlah");
        $gaji = uangPecah($this->input->post("gaji"));
        $sub_total = $gaji * $jumlah;

        $data = array("id_dgs"=>$id_dgs,"id_gaji_slip"=>$id_gaji_slip,"id_gaji"=>$id_gaji,"gaji_satuan"=>$gaji,"qty"=>$jumlah,"sub_total"=>$sub_total);
        $this->load->model("slipgaji_model");
        $this->slipgaji_model->simpan_data("detail_gaji_slip",$data);


        #update total
        $t = $this->slipgaji_model->cari_total("detail_gaji_slip",$id_gaji_slip);
        if($t->num_rows() > 0){
            foreach($t->result() as $h){
                $total = $h->total;
            }
        }
        else
        {
            $total = 0;
        }
        $data = array("total"=>$total);
        $where = array("id_gaji_slip"=>$id_gaji_slip);
        $this->slipgaji_model->update_data("gaji_slip",$data,$where);
        redirect(base_url('bayar-gaji/input/'.$this->enkripsi_url->encode($id_gaji_slip))); 

    }


    function bayar_list_hapus(){
        $id = $this->uri->segment(3);
        
        $where = array("id_dgs"=>$id);

        $this->load->model("slipgaji_model");
        $this->slipgaji_model->hapus_data("detail_gaji_slip",$where);
        
        
        #update total
        $id_gaji_slip = $this->uri->segment(4);
        $t = $this->slipgaji_model->cari_total("detail_gaji_slip",$id_gaji_slip);
        if($t->num_rows() > 0){
            foreach($t->result() as $h){
                $total = $h->total;
            }
        }
        else
        {
            $total = 0;
        }
        $data = array("total"=>$total);
        $where = array("id_gaji_slip"=>$id_gaji_slip);
        $this->slipgaji_model->update_data("gaji_slip",$data,$where);

        redirect(base_url('bayar-gaji/input/'.$this->enkripsi_url->encode($id_gaji_slip))); 
        
    }


    function bayar_selesai(){
        $id = $this->enkripsi_url->decode($this->input->post('id_selesai'));
        
        $data = array("status"=>"Selesai");
        $where = array("id_gaji_slip"=>$id);

        $this->load->model("slipgaji_model");
        $this->slipgaji_model->update_data("gaji_slip",$data,$where);
        

        redirect(base_url('bayar-gaji')); 
        
    }


    function bayar_cetak()
    {
        $id = $this->enkripsi_url->decode($this->uri->segment(3));
        $this->load->model("slipgaji_model");

        $data["slip"] = $this->slipgaji_model->tampil_data_edit($id);
        $data["list"] = $this->slipgaji_model->tampil_list_gaji_slip($id);
        $this->load->view("admin/bayar-gaji-cetak.php",$data);
    }

    function bayar_struk()
    {
        $id = $this->enkripsi_url->decode($this->uri->segment(3));
        $this->load->model("slipgaji_model");
        $slip = $this->slipgaji_model->tampil_data_edit($id);
        $list = $this->slipgaji_model->tampil_list_gaji_slip($id);
        
        $connector = new WindowsPrintConnector("Kasir USB");
        $printer = new Printer($connector);
        $printer -> feedReverse();
        try {
            foreach($slip->result() as $sl){
                $logo = EscposImage::load($_SERVER['DOCUMENT_ROOT'] . '/assets/foto/logo-kecil.png');
                $printer -> setJustification(Printer::JUSTIFY_CENTER);
                $printer->bitImage($logo);
            /* Name of shop */
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
            $printer->text("Arfa Karya Mandiri.\n");
            $printer->selectPrintMode();
            $printer->text("Nalumsari, Kab. Jepara.\n");
            $printer->text(date('d-m-Y')."\n");
            /* Barcode Default look */
             $printer -> setBarcodeHeight(48);
             $printer->setBarcodeTextPosition(Printer::BARCODE_TEXT_BELOW);
             $printer->barcode($sl->no_slip, Printer::BARCODE_CODE39);
        
        
            /* Title of receipt */
            $printer->setEmphasis(true);
            $printer->text("SLIP GAJI\n");
            $printer->setEmphasis(false);
        
            /* Items */
            $total = array();
            foreach($list->result() as $l){
                $printer -> setJustification(Printer::JUSTIFY_LEFT);
                $printer -> text($l->nama_gaji."\n");
                $printer -> setJustification(Printer::JUSTIFY_RIGHT);
                $printer -> text($l->qty." x ".uang($l->gaji_satuan)." \n");
                $total[] = $l->qty * $l->gaji_satuan;
            }
        
            
        
            /* Footer */
            /* bagian footer */
    $total = uang(array_sum($total));
    $panjang_total = strlen($total);
    $tunai = $total;
    $panjang_tunai = strlen($tunai);

    $printer -> setJustification(Printer::JUSTIFY_CENTER);
    $printer -> text("------------------------------------------------\n");
    $printer -> setJustification(Printer::JUSTIFY_RIGHT);
    $printer -> text("TOTAL GAJI : ".$total." \n");
    $printer -> text("PPH 21 : ".str_repeat(' ', $panjang_total)." \n");
    $printer -> text("TUNAI : ".str_repeat(' ', $panjang_tunai)." \n");
    $printer -> text("KEMBALI : ".str_repeat(' ', $panjang_total)." \n");
    $printer -> setJustification(Printer::JUSTIFY_CENTER);
    $printer -> text("-----------------------------------------------\n");
    $printer -> text("TERIMA KASIH \n");

    $ig = EscposImage::load($_SERVER['DOCUMENT_ROOT'] . '/assets/foto/ig-custom.png');
    $printer -> setJustification(Printer::JUSTIFY_LEFT);
    $printer->bitImage($ig); 
    $printer->feed();
        
            /* Cut the receipt and open the cash drawer */
            $printer->cut();
            $printer->pulse();
        }
        
        } catch (Exception $e) {
            echo $e->getMessage();
        } finally {
            $printer->close();
        }
        
        echo "<script>window.close();</script>";
    }

    

	
}
?>