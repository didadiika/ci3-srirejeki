<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once (dirname(__FILE__) . "/BaseController.php");

class Invoice extends BaseController{

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
        $this->load->view("admin/transaksi/invoice.php");
        $this->load->view("admin/template/footer.php");
        #Menampilkan halaman suplier dan mem passing variabel data#
    }
    
    function tampil(){
		#Mengambil data kain secara serverside#
		$this->load->model("invoice_model");
		$d = $this->invoice_model->make_datatables();
		$data = array();
		$start = isset($_GET["start"]) ? $_GET["start"] : 0;
		$no = $start + 1;
		foreach($d as $r){
		    /* Ambil akumulasi pembayaran */
            $bayar = 0;
            $by = $this->db->query("select sum(bayar) as bayar from invoice_bayar where id_invoice='$r->id_invoice' and deleted_at is null");
            if($by->num_rows() > 0){
                foreach($by->result() as $b){
                    $bayar = $b->bayar;
                }
            }
            /* -------------------------------------------------- */

			$sub_array = array();
			$sub_array[] = $no++;
            $sub_array[] = tgl_pecah($r->tanggal);
            $sub_array[] = $r->nama_pelanggan;
            $sub_array[] = $r->no_polisi;
            $sub_array[] = uang($r->harga_kbk);
            $sub_array[] = uang($r->total);
            
            if($r->status == "Proses")
            {
                $sub_array[] = '<span class="badge btn-warning">Proses</span>';
                if($r->status_bayar == "Belum Lunas"){ $sub_array[] = '<span class="badge btn-warning">Belum Lunas</span>'; } else {
                    $sub_array[] = '<span class="badge btn-success">Lunas</span>';
                }
                $sub_array[] = uang($r->total - $bayar);
                
                    $sub_array[] = '
                    <div class="btn-group">
                    <button type="button" class="btn btn-default">Pilih</button>
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                        <span class="caret"></span>
                        <span class="sr-only">Toggle Dropdown</span>
                    </button>
                        <ul class="dropdown-menu" role="menu">
                        <li><a href="'.base_url('transaksi/invoice/tambah-barang/'.$r->id_invoice).'">Tambah Barang</a></li>
                        <li><a href="javascript:;" class="item_hapus" data="'.$r->id_invoice.'" >Hapus</a></li>
                        </ul>
                    </div>';
                
                
            }
            else
            {
                $sub_array[] = '<span class="badge btn-primary">Selesai</span>';
                if($r->status_bayar == "Belum Lunas"){ 
                    $sub_array[] = '<span class="badge btn-warning">Belum Lunas</span>'; 
                    $sub_array[] = uang($r->total - $bayar);
                    $sub_array[] = '
                    <div class="btn-group">
                    <button type="button" class="btn btn-default">Pilih</button>
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                        <span class="caret"></span>
                        <span class="sr-only">Toggle Dropdown</span>
                    </button>
                        <ul class="dropdown-menu" role="menu">
                        <li><a href="'.base_url('transaksi/invoice/tambah-barang/'.$r->id_invoice).'">Lihat</a></li>
                        <li><a href="javascript:void(0)" data="'.$r->id_invoice.'" class="item_bayar"
                        tanggal-invoice="'.tgl_pecah($r->tanggal).'" pelanggan="'.$r->nama_pelanggan.'" 
                        total-invoice="'.(int)$r->total.'" dibayar="'.(int)$bayar.'" sisa="'.(int)($r->total - $bayar).'"
                        >Input Pembayaran</a></li>
                        <li><a href="javascript:void(0)" data="'.$r->id_invoice.'" class="item_riwayat">Riwayat Pembayaran</a></li>
                        <li><a href="'.base_url('transaksi/invoice/cetak-nota/'.$r->id_invoice).'" target="_blank">Cetak</a></li>
                        <li><a href="'.base_url('transaksi/invoice/surat-jalan/'.$r->id_invoice).'" target="_blank">Surat Jalan</a></li>
                        <li><a href="javascript:;" class="item_hapus" data="'.$r->id_invoice.'" >Hapus</a></li>
                        </ul>
                    </div>';
                } else {
                    $sub_array[] = '<span class="badge btn-success">Lunas</span>';
                    $sub_array[] = uang($r->total - $bayar);
                    $sub_array[] = '
                    <div class="btn-group">
                    <button type="button" class="btn btn-default">Pilih</button>
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                        <span class="caret"></span>
                        <span class="sr-only">Toggle Dropdown</span>
                    </button>
                        <ul class="dropdown-menu" role="menu">
                        <li><a href="'.base_url('transaksi/invoice/tambah-barang/'.$r->id_invoice).'">Lihat</a></li>
                        <li><a href="javascript:void(0)" data="'.$r->id_invoice.'" class="item_riwayat">Riwayat Pembayaran</a></li>
                        <li><a href="'.base_url('transaksi/invoice/cetak-nota/'.$r->id_invoice).'" target="_blank">Cetak</a></li>
                        <li><a href="'.base_url('transaksi/invoice/surat-jalan/'.$r->id_invoice).'" target="_blank">Surat Jalan</a></li>
                        <li><a href="javascript:;" class="item_hapus" data="'.$r->id_invoice.'" >Hapus</a></li>
                        </ul>
                    </div>';
                    
                }
                
            }
			
			$data[] = $sub_array;
		}
		#Mengambil data kategori di tabel kategori dan memasukkan ke variabel data#
		$draw = "";
		if(isset($_POST["draw"])){$draw = $_POST["draw"];}

			$output = array(
				"draw" 				=> intval($draw),
				"recordsTotal" 		=> $this->invoice_model->get_all_data(),
				"recordsFiltered" 	=> $this->invoice_model->get_filtered_data(),
				"data"				=> $data
		
		);

		echo json_encode($output);
	}

    function tambah_barang($id){
        

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
        #$id = $this->uri->segment(4);
        $this->load->model("pembelian_model");
        $data["invoice"] = $this->db->query("select * from invoice, pelanggan where invoice.id_invoice='$id' and invoice.id_pelanggan = pelanggan.id_pelanggan");
        $data["barang"] = $this->db->query("select * from invoice_d where invoice_d.id_invoice='$id' order by invoice_d.created_at asc");
        if($data["invoice"]->num_rows() > 0){
            foreach($data["invoice"]->result() as $r){
                $status = $r->status;
            }
        }
        $data["status"] = $status;
        $this->load->view("admin/transaksi/invoice-inputing.php",$data);
        }
        $this->load->view("admin/template/footer.php");
        #Menampilkan halaman suplier dan mem passing variabel data#
    }


    function buat(){
        $id_pelanggan = $this->input->post("id_pelanggan");
        $tanggal = tgl_pecah($this->input->post("tanggal"));
        $no_polisi = $this->input->post("no_polisi"); 
        $harga_kbk = 0;
        $kbk = $this->db->query("select * from sistem_setting ");
        if($kbk->num_rows() > 0){
            foreach($kbk->result() as $r){
                $harga_kbk = $r->kuli_naik_price;
            }
        }
        
        $id = id_primary();
        $data = array("id_invoice"=>$id,"id_pelanggan"=>$id_pelanggan,"no_polisi"=>$no_polisi,"tanggal"=>$tanggal,"harga_kbk"=>$harga_kbk,"status"=>"Proses");
        $this->load->model("invoice_model");
        $this->invoice_model->simpan_data("invoice",$data);
        
        #redirect(base_url('data/pengirim')); 
        
    }


    function cetak_nota($id){

        $data["invoice"] = $this->db->query("select * from invoice, pelanggan where invoice.id_pelanggan = pelanggan.id_pelanggan and 
        invoice.id_invoice='$id' and
        invoice.status='Selesai' and 
        invoice.deleted_at is null ");
        $data["barang"] = $this->db->query("select * from invoice_d where id_invoice='$id' order by created_at asc");
		$data["rekening"] = $this->db->query("select * from rekening, rekening_bank where selected='1' and rekening_bank.id_bank = rekening.id_bank");

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
        #Hapus tabel transaksi dan transaksi_invoice
        $p = $this->db->query("select * from transaksi_invoice where id_invoice='$id' ");
        if($p->num_rows() > 0){
            foreach($p->result() as $r){
                $this->db->query("update transaksi set deleted_at='".date("Y-m-d H:i:s")."' where id_transaksi='$r->id_transaksi' ");
            }
            $this->db->query("update transaksi_invoice set deleted_at='".date("Y-m-d H:i:s")."' where id_invoice='$id' ");
        }
        #Hapus Tabel invoice 
        $data = array("deleted_at"=>date("Y-m-d H:i:s"));
        $where = array("id_invoice"=>$id);

        $this->load->model("invoice_model");
        $this->invoice_model->update_data("invoice",$data,$where);
        

        redirect(base_url('transaksi/invoice')); 
        
    }

    function hapus_barang(){
        $id = $this->input->post('id_hapus');
        
        
        $p = $this->db->query("select * from invoice_d where id_id='$id' ");
        if($p->num_rows() > 0){

            foreach($p->result() as $r){
                $id_invoice = $r->id_invoice;
            }
            
            $this->db->query("delete from invoice_d where id_id='$id' ");

            $tot = $this->db->query("select sum(sub_total) as b, sum(qty) as q from invoice_d where id_invoice='$id_invoice' ");
            $total = 0;
            $total_tonase = 0;
            if($tot->num_rows() > 0){
                foreach($tot->result() as $t){
                    $total = $t->b;
                    $total_tonase = $t->q;
                }
                $this->db->query("update invoice set total_tonase='$total_tonase',total= '$total' where id_invoice='$id_invoice' ");
            }

        }
        

        redirect(base_url('transaksi/pembelian')); 
        
    }

    function update(){

        
    }

    function selesai(){
        $id = $this->input->post('id_selesai');
        
        $data = array("status"=>"Selesai");
        $where = array("id_invoice"=>$id);

        $this->load->model("invoice_model");
        $this->invoice_model->update_data("invoice",$data,$where);

        $p = $this->db->query("select * from invoice where id_invoice='$id' ");
        if($p->num_rows() > 0){
            foreach($p->result() as $r){
                $tanggal = $r->tanggal;
                $no_polisi = $r->no_polisi;
                $pembayaran_kuli = $r->total_tonase * $r->harga_kbk;
            }
        #-------------------------------------------------------------------#
            #Insert pengeluaran KULI ANGKUT ke tabel transaksi
            $id_transaksi = id_primary();
            $keterangan = "Pembayaran Kuli Angkut (".$no_polisi.")";
            $data = array("id_transaksi"=>$id_transaksi,
                        "nota"=>NULL,
                        "tanggal"=>$tanggal,
                        "keterangan"=>$keterangan,
                        "debit"=>0,
                        "kredit"=>$pembayaran_kuli,
                        "jenis"=>"Pengeluaran"
                    );
            $this->invoice_model->simpan_data("transaksi",$data);
            #Insert ke tabel transaksi_invoice
            $data = array("id_ti"=>id_primary(),
                        "id_invoice"=>$id,
                        "id_transaksi"=>$id_transaksi
                    );
            $this->invoice_model->simpan_data("transaksi_invoice",$data);
        }

        redirect(base_url('transaksi/invoice')); 
        
    }


    function tambah(){
        $id = id_primary();
        $id_invoice = $this->input->post("id_invoice");
        $barang = $this->input->post("barang");
        $qty = $this->input->post("qty");
        $harga = uangPecah($this->input->post("harga"));
        $sub_total = uangPecah($this->input->post("sub_total"));

        $this->db->query("insert into invoice_d (id_id, id_invoice, nama_barang, qty, harga, sub_total) 
        values ('$id','$id_invoice','$barang','$qty','$harga','$sub_total')");
        $tot = $this->db->query("select sum(sub_total) as b, sum(qty) as q from invoice_d where id_invoice='$id_invoice' ");
        $total = 0;
        $total_tonase = 0;
        if($tot->num_rows() > 0){
            foreach($tot->result() as $t){
                $total = $t->b;
                $total_tonase = $t->q;
            }
        }
        $this->db->query("update invoice set total_tonase='$total_tonase',total = '$total' where id_invoice='$id_invoice' ");
       

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

    function simpan(){
        $id = $this->input->post("id");
        $harga_kbk = $this->input->post("harga_kbk");
        
        $data = array("harga_kbk"=>$harga_kbk,"updated_at"=>date("Y-m-d H:i:s"));
        $where = array("id_invoice"=>$id);
        $this->load->model("invoice_model");
        $this->invoice_model->update_data("invoice",$data,$where);
        
        redirect(base_url('transaksi/invoice')); 

    }



    function cetak()
    {
        $id = $this->enkripsi_url->decode($this->uri->segment(4));
        $this->load->model("pembelian_model");

        $data["pembelian"] = $this->pembelian_model->tampil_data_edit($id);
        $data["keranjang"] = $this->db->query("select pembelian_d.* from pembelian_d where
        pembelian_d.id_pembelian='$id' ");
        $this->load->view("admin/transaksi/pembelian-cetak.php",$data);
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
        $id_invoice = $this->input->post('id_invoice');

            $data = array("deleted_at"=>date("Y-m-d H:i:s")
                    );
            $where = array("id_ib"=>$id_hapus);
            $this->invoice_model->update_data("invoice_bayar",$data,$where);
        #-----Hapus Pembayaran------#

        #-----Kontrol Pelunasan------#
        $total_bayar = 0;
        $t = $this->db->query("select sum(bayar) as b  from invoice_bayar where id_invoice='$id_invoice' ");
        if($t->num_rows() > 0){
            foreach($t->result() as $r){
                $total_bayar = $r->b;
            }
        }
        if($total_bayar >= $total_tagihan){
            $this->db->query("update invoice set status_bayar='Lunas' where id_invoice='$id_invoice' ");
        } else {
            $this->db->query("update invoice set status_bayar='Belum Lunas' where id_invoice='$id_invoice' ");
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