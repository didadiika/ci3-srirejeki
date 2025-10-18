<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once (dirname(__FILE__) . "/BaseController.php");

class Gaji extends BaseController{

	function __construct(){
		parent::__construct();

		$cek_login = $this->session->userdata('authenticated');
        if ($cek_login != true) {
			redirect( base_url('login') );
        }

	}

	
	function index(){
        
        
        
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
        $this->load->view("admin/transaksi/gaji-karyawan.php");
        $this->load->view("admin/template/footer.php");
        #Menampilkan halaman suplier dan mem passing variabel data#
    }
    
    function tampil(){
		#Mengambil data kain secara serverside#
		$this->load->model("gaji_model");
		$d = $this->gaji_model->make_datatables();
		$data = array();
		$start = isset($_GET["start"]) ? $_GET["start"] : 0;
		$no = $start + 1;
		foreach($d as $r){
		    /* Ambil akumulasi pembayaran */
            
            

            /* -------------------------------------------------- */

			$sub_array = array();
			$sub_array[] = $no++;
            $sub_array[] = tgl_pecah($r->tanggal);
            $sub_array[] = $r->keterangan;
            $sub_array[] = uang($r->total);
            
            if($r->status == "Proses")
            {
                $sub_array[] = '<span class="badge btn-warning">Proses</span>';
                
                    $sub_array[] = '
                    <div class="btn-group">
                    <button type="button" class="btn btn-default">Pilih</button>
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                        <span class="caret"></span>
                        <span class="sr-only">Toggle Dropdown</span>
                    </button>
                        <ul class="dropdown-menu" role="menu">
                        <li><a href="'.base_url('transaksi/gaji-karyawan/tambah-gaji/'.$r->id_ka).'">Tambah Gaji</a></li>
                        <li><a href="javascript:;" class="item_hapus" data="'.$r->id_ka.'" >Hapus</a></li>
                        </ul>
                    </div>';
                
                
            }
            else
            {
                $sub_array[] = '<span class="badge btn-primary">Selesai</span>';
                    $sub_array[] = '
                    <div class="btn-group">
                    <button type="button" class="btn btn-default">Pilih</button>
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                        <span class="caret"></span>
                        <span class="sr-only">Toggle Dropdown</span>
                    </button>
                        <ul class="dropdown-menu" role="menu">
                        <li><a href="'.base_url('transaksi/gaji-karyawan/tambah-gaji/'.$r->id_ka).'">Lihat</a></li>
                        <li><a href="'.base_url('transaksi/gaji-karyawan/cetak/'.$r->id_ka).'" target="_blank">Cetak</a></li>
                        <li><a href="javascript:;" class="item_hapus" data="'.$r->id_ka.'" >Hapus</a></li>
                        </ul>
                    </div>';
                    
                
                
            }
			
			$data[] = $sub_array;
		}
		#Mengambil data kategori di tabel kategori dan memasukkan ke variabel data#
		$draw = "";
		if(isset($_POST["draw"])){$draw = $_POST["draw"];}

			$output = array(
				"draw" 				=> intval($draw),
				"recordsTotal" 		=> $this->gaji_model->get_all_data(),
				"recordsFiltered" 	=> $this->gaji_model->get_filtered_data(),
				"data"				=> $data
		
		);

		echo json_encode($output);
	}

    function tambah_gaji($id){
        

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

        
        if($id)
        {
        $g = 0;
        $ga = $this->db->query("select * from sistem_setting ");
        if($ga->num_rows() > 0){
            foreach($ga->result() as $r){
                $g = $r->gaji_karyawan;
            }
        }
        $this->load->model("pembelian_model");
        $data["gaji"] = $this->db->query("select * from karyawan_abs where id_ka='$id' ");
        $data["karyawan"] = $this->db->query("select * from karyawan_abs_d, karyawan where karyawan_abs_d.id_ka='$id' and 
        karyawan_abs_d.id_karyawan = karyawan.id_karyawan order by karyawan_abs_d.created_at asc");
        $data["kar"] = $this->db->query("select * from karyawan where deleted_at is null order by nama_karyawan");
        if($data["gaji"]->num_rows() > 0){
            foreach($data["gaji"]->result() as $r){
                $status = $r->status;
            }
        }
        $data["status"] = $status;
        $data["setting_gaji"] = (int)$g;
        $this->load->view("admin/transaksi/gaji-karyawan-inputing.php",$data);
        }
        $this->load->view("admin/template/footer.php");
        #Menampilkan halaman suplier dan mem passing variabel data#
    }


    function buat(){
        
        $tanggal = tgl_pecah($this->input->post("tanggal"));
        $keterangan = $this->input->post("keterangan");
        
        $id = id_primary();
        $data = array("id_ka"=>$id,"tanggal"=>$tanggal,"keterangan"=>$keterangan,"status"=>"Proses");
        $this->load->model("gaji_model");
        $this->gaji_model->simpan_data("karyawan_abs",$data);
        
        #redirect(base_url('data/pengirim')); 
        
    }


    function cetak_nota($id){

        $data["invoice"] = $this->db->query("select * from invoice, pelanggan where invoice.id_pelanggan = pelanggan.id_pelanggan and 
        invoice.id_invoice='$id' and
        invoice.status='Selesai' and 
        invoice.deleted_at is null ");
        $data["barang"] = $this->db->query("select * from invoice_d where id_invoice='$id' order by created_at asc");
		$data["rekening"] = $this->db->query("select * from rekening order by id_rekening desc");

		#Menampilkan halaman#
		$this->load->view("admin/transaksi/invoice-cetak-tampil.php",$data);
        #Menampilkan halaman#

    }

    function surat_jalan($id){

        $data["invoice"] = $this->db->query("select * from invoice, pelanggan where invoice.id_pelanggan = pelanggan.id_pelanggan and 
        invoice.id_invoice='$id' and
        invoice.status='Selesai' and 
        invoice.deleted_at is null ");
        $data["barang"] = $this->db->query("select * from invoice_d where id_invoice='$id' order by created_at asc");
		$data["rekening"] = $this->db->query("select * from rekening order by id_rekening desc");

		#Menampilkan halaman#
		$this->load->view("admin/transaksi/invoice-surat-jalan-tampil.php",$data);
        #Menampilkan halaman#

    }

    function pelanggan_cari(){
        $cari = trim($this->input->get("cari"));
        $page = $this->input->get("page");#Untuk Pagination
        $batas = $this->input->get("batas");
        $offset = ($page - 1) * $batas;
        if(!empty($cari))
        {   
            $query = $this->db->query("select * from pelanggan where 
            deleted_at is null and
            nama_pelanggan like '%$cari%' order by nama_pelanggan asc
            limit $offset,$batas
            ");

            $count_filtered = $query->num_rows();
            $count_all = $this->db->query("select * from pelanggan where 
            deleted_at is null and
            nama_pelanggan like '%$cari%' order by nama_pelanggan asc
            ")->num_rows();
            
            if($query->num_rows() > 0){
            $list = array();
            foreach($query->result() as $row) {
                    $list[] = array("id"=>$row->id_pelanggan, "text"=>$row->nama_pelanggan);
                
            }
            $hasil = array("pelanggan" => $list,
            "count_filtered" => $count_all
            );
            echo json_encode($hasil);
            }
            else
            {
                echo "Tidak Ketemu";
            }
            
        }
        else
        {
            echo "Tidak Valid";
        }
    }

    function hapus(){
        $id = $this->input->post('id_hapus');       
        #Hapus tabel transaksi dan transaksi_abs
        $p = $this->db->query("select * from transaksi_abs where id_ka='$id' ");
        if($p->num_rows() > 0){
            foreach($p->result() as $r){
                $this->db->query("delete from transaksi where id_transaksi='$r->id_transaksi' ");
            }
            $this->db->query("delete from transaksi_abs where id_ka='$id' ");
        }
        #Hapus Tabel karyawan_abs
        $where = array("id_ka"=>$id);

        $this->load->model("gaji_model");
        $this->gaji_model->hapus_data("karyawan_abs",$where);
        

        redirect(base_url('transaksi/gaji-karyawan')); 
        
    }

    function hapus_barang(){
        $id = $this->input->post('id_hapus');
        
        
        $p = $this->db->query("select * from invoice_d where id_id='$id' ");
        if($p->num_rows() > 0){

            foreach($p->result() as $r){
                $id_ka = $r->id_ka;
            }
            
            $this->db->query("delete from invoice_d where id_id='$id' ");

            $tot = $this->db->query("select sum(sub_total) as b, sum(qty) as q from invoice_d where id_invoice='$id_ka' ");
            $total = 0;
            $total_tonase = 0;
            if($tot->num_rows() > 0){
                foreach($tot->result() as $t){
                    $total = $t->b;
                    $total_tonase = $t->q;
                }
                $this->db->query("update invoice set total_tonase='$total_tonase',total= '$total' where id_invoice='$id_ka' ");
            }

        }
        

        redirect(base_url('transaksi/pembelian')); 
        
    }

    function update(){

        
    }

    function selesai(){
        $id = $this->input->post('id_selesai');
        
        $data = array("status"=>"Selesai");
        $where = array("id_ka"=>$id);

        $this->load->model("gaji_model");
        $this->gaji_model->update_data("karyawan_abs",$data,$where);

        $p = $this->db->query("select * from karyawan_abs where id_ka='$id' ");
        if($p->num_rows() > 0){
            foreach($p->result() as $r){
                $tanggal = $r->tanggal;
                $gaji = $r->total;
                $keterangan = $r->keterangan;
            }
        #-------------------------------------------------------------------#
            #Insert pengeluaran GAJI ke tabel transaksi
            $id_transaksi = id_primary();
            $keterangan = "GAJI KARYAWAN :".$keterangan;
            $data = array("id_transaksi"=>$id_transaksi,
                        "nota"=>NULL,
                        "tanggal"=>$tanggal,
                        "keterangan"=>$keterangan,
                        "debit"=>0,
                        "kredit"=>$gaji,
                        "jenis"=>"Pengeluaran"
                    );
            $this->gaji_model->simpan_data("transaksi",$data);
            #Insert ke tabel transaksi_abs
            $data = array("id_ta"=>id_primary(),
                        "id_ka"=>$id,
                        "id_transaksi"=>$id_transaksi
                    );
            $this->gaji_model->simpan_data("transaksi_abs",$data);
        }

        redirect(base_url('transaksi/invoice')); 
        
    }


    function tambah(){
        
        $id_ka = $this->input->post("id_ka");
        $jml = $this->input->post("jml");
        $items = $this->input->post("items");
        $qty = $this->input->post("qty");
        $gaji = $this->input->post("gaji");
        $potongan = $this->input->post("potongan");
        $sub_total = $this->input->post("sub_total");

        
        $this->db->query("delete from karyawan_abs_d where id_ka='$id_ka' ");
        foreach($items as $id => $data){
            $id_kad = id_primary();
            $id_karyawan = $data['id'];
            $qty = $data['qty'];
            $gaji = uangPecah($data['gaji']);
            $potongan = uangPecah($data['potongan']);
            $sub_total = $qty * $gaji - $potongan;
           
            $this->db->query("insert into karyawan_abs_d (id_kad,id_ka,id_karyawan,qty,gaji,potongan,sub_total) values ('$id_kad','$id_ka','$id_karyawan','$qty','$gaji','$potongan','$sub_total') ");
            $tot = $this->db->query("select sum(sub_total) as b from karyawan_abs_d where id_ka='$id_ka' ");
            $total = 0;
            if($tot->num_rows() > 0){
                foreach($tot->result() as $t){
                    $total = $t->b;
                }
            $this->db->query("update karyawan_abs set total='$total' where id_ka='$id_ka' ");
            }
        }
       

    }


    function list_hapus(){
        $id = $this->uri->segment(3);
        
        $where = array("id_d_beli"=>$id);

        $this->load->model("pembelian_d_model");
        $this->pembelian_d_model->hapus_data("pembelian_d",$where);
        
        
        #update total
        $id_pembelian = $this->uri->segment(4);
        $this->load->model("pembelian_model");
        $t = $this->pembelian_model->cari_total($id_pembelian);
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
        $where = array("id_pembelian"=>$id_pembelian);
        $this->pembelian_model->update_data("pembelian",$data,$where);

        redirect(base_url('transaksi/pembelian/input/'.$this->enkripsi_url->encode($id_pembelian))); 
        
    }



    function cetak($id)
    {
        $this->load->model("gaji_model");

        $data["gaji"] = $this->db->query("select * from karyawan_abs where id_ka='$id' ");
        $data["karyawan"] = $this->db->query("select * from karyawan_abs_d, karyawan where karyawan_abs_d.id_ka='$id' and karyawan_abs_d.id_karyawan = karyawan.id_karyawan
        order by karyawan.nama_karyawan asc ");
        $this->load->view("admin/transaksi/gaji-karyawan-cetak.php",$data);
    }



    function simpan_pembayaran(){
        #-----Simpan Pembayaran------#
        $this->load->model("invoice_model");
        $id_bayar = $this->input->post('id_bayar');
        $tanggal = tgl_pecah($this->input->post('tanggal'));
        $bayar = uangPecah($this->input->post('bayar'));
        $total_tagihan = uangPecah($this->input->post('total_invoice'));

            $id_ib = id_primary();
            $data = array("id_ib"=>$id_ib,
                        "id_invoice"=>$id_bayar,
                        "tanggal"=>$tanggal,
                        "bayar"=>$bayar
                    );
            $this->invoice_model->simpan_data("invoice_bayar",$data);
        #-----Simpan Pembayaran------#

        #-----Kontrol Pelunasan------#
        $total_bayar = 0;
        $t = $this->db->query("select sum(bayar) as b  from invoice_bayar where id_invoice='$id_bayar' ");
        if($t->num_rows() > 0){
            foreach($t->result() as $r){
                $total_bayar = $r->b;
            }
        }
        if($total_bayar >= $total_tagihan){
            $this->db->query("update invoice set status_bayar='Lunas' where id_invoice='$id_bayar' ");
        } else {
            $this->db->query("update invoice set status_bayar='Belum Lunas' where id_invoice='$id_bayar' ");
        }
        #-----Kontrol Pelunasan-----#

    }

    function hapus_pembayaran(){
        #-----Hapus Pembayaran------#
        $this->load->model("invoice_model");
        $id_hapus = $this->input->post('id_hapus');
        $id_ka = $this->input->post('id_invoice');

            $data = array("deleted_at"=>date("Y-m-d H:i:s")
                    );
            $where = array("id_ib"=>$id_hapus);
            $this->invoice_model->update_data("invoice_bayar",$data,$where);
        #-----Hapus Pembayaran------#

        #-----Kontrol Pelunasan------#
        $total_bayar = 0;
        $t = $this->db->query("select sum(bayar) as b  from invoice_bayar where id_invoice='$id_ka' ");
        if($t->num_rows() > 0){
            foreach($t->result() as $r){
                $total_bayar = $r->b;
            }
        }
        if($total_bayar >= $total_tagihan){
            $this->db->query("update invoice set status_bayar='Lunas' where id_invoice='$id_ka' ");
        } else {
            $this->db->query("update invoice set status_bayar='Belum Lunas' where id_invoice='$id_ka' ");
        }
        #-----Kontrol Pelunasan-----#
    }

	function riwayat_pembayaran($id)
    {
        $this->load->model("invoice_model");

        $data["invoice"] = $this->db->query("select * from invoice, pelanggan where invoice.id_invoice='$id' and invoice.id_pelanggan = pelanggan.id_pelanggan");
        $data["pembayaran"] = $this->db->query("select * from invoice_bayar where id_invoice='$id' order by tanggal asc, created_at asc");
        $this->load->view("admin/transaksi/invoice-history-pembayaran-tampil.php",$data);
    }
}
?>