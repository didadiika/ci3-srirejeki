<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once (dirname(__FILE__) . "/BaseController.php");

class Laporan extends BaseController{

	function __construct(){
		parent::__construct();

		$cek_login = $this->session->userdata('authenticated');
        if ($cek_login != true) {
			redirect( base_url('login') );
        }

	}

	
	function index(){
		

    }

    

    

    function laporan_pembelian(){
       
        $level = $this->session->level;
        $data['kategori'] = $this->db->get('kategori')->result();
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
        $data["pengirim"] = $this->db->query("select * from pengirim where deleted_at is null");
        $this->load->view("admin/laporan/laporan-pembelian.php",$data);
        $this->load->view("admin/template/footer.php");

    }

    function laporan_pembelian_tampil(){
        $this->load->model("transaksi_model");
        $id_pengirim = $this->input->post("id_pengirim");
        $id_kategori = $this->input->post("id_kategori");
        $dari = $this->input->post("dari");
		$sampai = $this->input->post("sampai");
        $nama_bulan = array(1=>"Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
		$data["lap"] = array(
					"dari"=>substr($dari,0,2)."-".$nama_bulan[(int)substr($dari,3,2)]."-".substr($dari,6,4),
					"sampai"=>substr($sampai,0,2)."-".$nama_bulan[(int)substr($sampai,3,2)]."-".substr($sampai,6,4));

        if($id_pengirim == "*" && $id_kategori == "*"){
                
            $data["gaji"] = $this->db->query("select * from pembelian, pengirim, kategori where 
            pembelian.id_pengirim = pengirim.id_pengirim and 
            pembelian.id_kategori = kategori.id and
            (pembelian.tanggal between '".tgl_pecah($dari)."' and '".tgl_pecah($sampai)."' ) and 
            pembelian.status='Selesai' and 
            pembelian.deleted_at is null order by pembelian.tanggal asc");
            $data["pengirim"] = "Semua Pengirim";
            $data["kategori"] = "Semua Kategori";
            $data["sudah_bayar"] = $this->db->query("select sum(pembelian_bayar.bayar) as paid from pembelian, pembelian_bayar where 
            pembelian.id_pembelian = pembelian_bayar.id_pembelian and 
            (pembelian.tanggal between '".tgl_pecah($dari)."' and '".tgl_pecah($sampai)."' ) and 
            pembelian.status='Selesai' and 
            pembelian.deleted_at is null ");
        } else if($id_pengirim == "*" && $id_kategori != "*"){
            
            $data["gaji"] = $this->db->query("select * from pembelian, pengirim, kategori where 
            pembelian.id_pengirim = pengirim.id_pengirim and 
            pembelian.id_kategori = kategori.id and
            (pembelian.tanggal between '".tgl_pecah($dari)."' and '".tgl_pecah($sampai)."' ) and 
            pembelian.id_kategori='$id_kategori' and
            pembelian.status='Selesai' and 
            pembelian.deleted_at is null order by pembelian.tanggal asc");
            $kt = $this->db->query("select * from kategori where id='$id_kategori' ");
            if($kt->num_rows() > 0){
                foreach($kt->result() as $k){
                    $data["kategori"] = $k->nama_kategori;
                }
            }
            $data["pengirim"] = "Semua Pengirim";
            $data["sudah_bayar"] = $this->db->query("select sum(pembelian_bayar.bayar) as paid from pembelian, pembelian_bayar where 
            pembelian.id_pembelian = pembelian_bayar.id_pembelian and 
            (pembelian.tanggal between '".tgl_pecah($dari)."' and '".tgl_pecah($sampai)."' ) and 
            pembelian.id_kategori='$id_kategori' and
            pembelian.status='Selesai' and 
            pembelian.deleted_at is null ");
        } else if($id_pengirim != "*" && $id_kategori == "*"){
            
            $pengirim = $this->input->post("pengirim");
                $i = 0;
                foreach($pengirim as $x){
                    if($i == 0){
                        $d = "'".$x."'";
                    }else{
                        $d .= ",'".$x."'";
                    }
                    $i++;
                }

            $kn = $this->db->query("select * from pengirim where id_pengirim in (".$d.")");
            if($kn->num_rows() > 0)
            {
                
                $h = 0 ;
                foreach($kn->result() as $nn){
                    if($h == 0){
                        $d_name = $nn->nama_pengirim;
                    }else{
                        $d_name .= ",".$nn->nama_pengirim;
                    }
                    $h++;
                }
                $data["pengirim"] = $d_name;
            }
            $data["kategori"] = "Semua Kategori";
            $data["gaji"] = $this->db->query("select * from pembelian, pengirim, kategori where 
            pembelian.id_pengirim = pengirim.id_pengirim and 
            pembelian.id_kategori = kategori.id and
            (pembelian.tanggal between '".tgl_pecah($dari)."' and '".tgl_pecah($sampai)."' ) and 
            pembelian.id_pengirim in (".$d.") and
            pembelian.status='Selesai' and 
            pembelian.deleted_at is null order by pembelian.tanggal asc");
            $data["sudah_bayar"] = $this->db->query("select sum(pembelian_bayar.bayar) as paid from pembelian, pembelian_bayar where 
            pembelian.id_pembelian = pembelian_bayar.id_pembelian and 
            (pembelian.tanggal between '".tgl_pecah($dari)."' and '".tgl_pecah($sampai)."' ) and 
            pembelian.id_pengirim in (".$d.") and
            pembelian.status='Selesai' and 
            pembelian.deleted_at is null ");

        } else {
            $kt = $this->db->query("select * from kategori where id='$id_kategori' ");
            if($kt->num_rows() > 0){
                foreach($kt->result() as $k){
                    $data["kategori"] = $k->nama_kategori;
                }
            }
            $pengirim = $this->input->post("pengirim");
                $i = 0;
                foreach($pengirim as $x){
                    if($i == 0){
                        $d = "'".$x."'";
                    }else{
                        $d .= ",'".$x."'";
                    }
                    $i++;
                }

            $kn = $this->db->query("select * from pengirim where id_pengirim in (".$d.")");
            if($kn->num_rows() > 0)
            {
                
                $h = 0 ;
                foreach($kn->result() as $nn){
                    if($h == 0){
                        $d_name = $nn->nama_pengirim;
                    }else{
                        $d_name .= ",".$nn->nama_pengirim;
                    }
                    $h++;
                }
                $data["pengirim"] = $d_name;
            }

            $data["gaji"] = $this->db->query("select * from pembelian, pengirim, kategori where pembelian.id_pengirim = pengirim.id_pengirim and 
            pembelian.id_kategori = kategori.id and
            (pembelian.tanggal between '".tgl_pecah($dari)."' and '".tgl_pecah($sampai)."' ) and 
            pembelian.id_pengirim in (".$d.") and
            pembelian.id_kategori='$id_kategori' and
            pembelian.status='Selesai' and 
            pembelian.deleted_at is null order by pembelian.tanggal asc");

            $data["sudah_bayar"] = $this->db->query("select sum(pembelian_bayar.bayar) as paid from pembelian, pembelian_bayar where 
            pembelian.id_pembelian = pembelian_bayar.id_pembelian and 
            (pembelian.tanggal between '".tgl_pecah($dari)."' and '".tgl_pecah($sampai)."' ) and 
            pembelian.id_pengirim in (".$d.") and
            pembelian.id_kategori='$id_kategori' and
            pembelian.status='Selesai' and 
            pembelian.deleted_at is null ");
        }
		#Menampilkan halaman#
		$this->load->view("admin/laporan/laporan-pembelian-tampil.php",$data);
        #Menampilkan halaman#
	}


    function laporan_penjualan(){
       
        $level = $this->session->level;
        $data['kategori'] = $this->db->get('kategori')->result();
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
        $data["pelanggan"] = $this->db->query("select * from pelanggan where deleted_at is null");
        $this->load->view("admin/laporan/laporan-penjualan.php",$data);
        $this->load->view("admin/template/footer.php");

    }

    function laporan_penjualan_tampil(){
        $id_kategori = $this->input->post("id_kategori");
		$jenis = $this->input->post("jenis");
        $pelanggan = $this->input->post("id_pelanggan");
		$dari = $this->input->post("dari");
		$sampai = $this->input->post("sampai");
        $nama_bulan = array(1=>"Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
        
        $data["jenis"] = $jenis;
		$data["lap"] = array(
					"dari"=>substr($dari,0,2)."-".$nama_bulan[(int)substr($dari,3,2)]."-".substr($dari,6,4),
					"sampai"=>substr($sampai,0,2)."-".$nama_bulan[(int)substr($sampai,3,2)]."-".substr($sampai,6,4));
        $this->load->model("transaksi_model");
		

        if($jenis == "Tampil per Nota")
        {
            if($pelanggan == "*" && $id_kategori == "*"){
                $data["gaji"] = $this->db->query("select * from pelanggan, invoice where invoice.id_pelanggan = pelanggan.id_pelanggan and 
                (invoice.tanggal between '".tgl_pecah($dari)."' and '".tgl_pecah($sampai)."' ) and 
                invoice.status='Selesai' and 
                invoice.deleted_at is null order by invoice.tanggal asc");
                $data["pelanggan"] = "Semua Pelanggan";
                $data["kategori"] = "Semua Kategori";
                } 
                else if($pelanggan == "*" && $id_kategori != "*"){
                $data["gaji"] = $this->db->query("select * from pelanggan, invoice where invoice.id_pelanggan = pelanggan.id_pelanggan and 
                (invoice.tanggal between '".tgl_pecah($dari)."' and '".tgl_pecah($sampai)."' ) and 
                invoice.id_kategori='$id_kategori' and
                invoice.status='Selesai' and 
                invoice.deleted_at is null order by invoice.tanggal asc");
                $data["pelanggan"] = "Semua Pelanggan";
                $data["kategori"] = $this->db->get_where('kategori', array('id' => $id_kategori))->row()->nama_kategori;
                }
                else if($pelanggan != "*" && $id_kategori == "*"){
                    $data["gaji"] = $this->db->query("select * from pelanggan, invoice where invoice.id_pelanggan = pelanggan.id_pelanggan and 
                    (invoice.tanggal between '".tgl_pecah($dari)."' and '".tgl_pecah($sampai)."' ) and 
                    invoice.id_pelanggan='$pelanggan' and
                    invoice.status='Selesai' and 
                    invoice.deleted_at is null order by invoice.tanggal asc");
                    $pen = $this->db->query("select * from pelanggan where id_pelanggan='$pelanggan' ");
                    if($pen->num_rows() > 0){
                        foreach($pen->result() as $p){
                            $data["pelanggan"] = $p->nama_pelanggan;
                        }
                    }
                    $data["kategori"] = "Semua Kategori";
                }
                else if($pelanggan != "*" && $id_kategori != "*") {
                    $data["gaji"] = $this->db->query("select * from pelanggan, invoice where invoice.id_pelanggan = pelanggan.id_pelanggan and 
                    (invoice.tanggal between '".tgl_pecah($dari)."' and '".tgl_pecah($sampai)."' ) and 
                    invoice.id_pelanggan='$pelanggan' and
                    invoice.id_kategori='$id_kategori' and
                    invoice.status='Selesai' and 
                    invoice.deleted_at is null order by invoice.tanggal asc");
                    $pen = $this->db->query("select * from pelanggan where id_pelanggan='$pelanggan' ");
                    if($pen->num_rows() > 0){
                        foreach($pen->result() as $p){
                            $data["pelanggan"] = $p->nama_pelanggan;
                        }
                    }
                    $data["kategori"] = $this->db->get_where('kategori', array('id' => $id_kategori))->row()->nama_kategori;
                    
                }
            #Menampilkan halaman#
            $this->load->view("admin/laporan/laporan-penjualan-per-nota-tampil.php",$data);
            #Menampilkan halaman#
        } else if($jenis == "Tampil Rinci"){
            if($pelanggan == "*" && $id_kategori == "*"){
                    $data["gaji"] = $this->db->query("select * from pelanggan, invoice, invoice_d where invoice.id_pelanggan = pelanggan.id_pelanggan and 
                    (invoice.tanggal between '".tgl_pecah($dari)."' and '".tgl_pecah($sampai)."' ) and 
                    invoice.id_invoice = invoice_d.id_invoice and
                    invoice.status='Selesai' and 
                    invoice.deleted_at is null order by invoice.tanggal asc");
                    $data["pelanggan"] = "Semua Pelanggan";
                    $data["kategori"] = "Semua Kategori";
                }
                else if($pelanggan == "*" && $id_kategori != "*"){
                    $data["gaji"] = $this->db->query("select * from pelanggan, invoice, invoice_d where invoice.id_pelanggan = pelanggan.id_pelanggan and 
                    (invoice.tanggal between '".tgl_pecah($dari)."' and '".tgl_pecah($sampai)."' ) and 
                    invoice.id_invoice = invoice_d.id_invoice and
                    invoice.id_kategori='$id_kategori' and
                    invoice.status='Selesai' and 
                    invoice.deleted_at is null order by invoice.tanggal asc");
                    $data["pelanggan"] = "Semua Pelanggan";
                    $data["kategori"] = $this->db->get_where('kategori', array('id' => $id_kategori))->row()->nama_kategori;
                }
                else if($pelanggan != "*" && $id_kategori == "*"){
                    $data["gaji"] = $this->db->query("select * from pelanggan, invoice, invoice_d where invoice.id_pelanggan = pelanggan.id_pelanggan and 
                    (invoice.tanggal between '".tgl_pecah($dari)."' and '".tgl_pecah($sampai)."' ) and 
                    invoice.id_invoice = invoice_d.id_invoice and
                    invoice.id_pelanggan='$pelanggan' and
                    invoice.status='Selesai' and 
                    invoice.deleted_at is null order by invoice.tanggal asc");
                    $pen = $this->db->query("select * from pelanggan where id_pelanggan='$pelanggan' ");
                    if($pen->num_rows() > 0){
                        foreach($pen->result() as $p){
                            $data["pelanggan"] = $p->nama_pelanggan;
                        }
                    }
                    $data["kategori"] = "Semua Kategori";
                }
                else if($pelanggan != "*" && $id_kategori != "*"){
                    $data["gaji"] = $this->db->query("select * from pelanggan, invoice, invoice_d where invoice.id_pelanggan = pelanggan.id_pelanggan and 
                    (invoice.tanggal between '".tgl_pecah($dari)."' and '".tgl_pecah($sampai)."' ) and 
                    invoice.id_invoice = invoice_d.id_invoice and
                    invoice.id_pelanggan='$pelanggan' and
                    invoice.id_kategori='$id_kategori' and
                    invoice.status='Selesai' and 
                    invoice.deleted_at is null order by invoice.tanggal asc");
                    $pen = $this->db->query("select * from pelanggan where id_pelanggan='$pelanggan' ");
                    if($pen->num_rows() > 0){
                        foreach($pen->result() as $p){
                            $data["pelanggan"] = $p->nama_pelanggan;
                        }
                    }
                    $data["kategori"] = $this->db->get_where('kategori', array('id' => $id_kategori))->row()->nama_kategori;
                }
            #Menampilkan halaman#
            $this->load->view("admin/laporan/laporan-penjualan-rinci-tampil.php",$data);
            #Menampilkan halaman#
        }		
	}

    function laporan_piutang(){
       
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
        $data["pelanggan"] = $this->db->query("select * from pelanggan where deleted_at is null");
        $this->load->view("admin/laporan/laporan-piutang.php",$data);
        $this->load->view("admin/template/footer.php");

    }

    function laporan_piutang_tampil(){
		$pelanggan = $this->input->post("id_pelanggan");
		
        $this->load->model("invoice_model");
		
		
		$data["gaji"] = $this->db->query("select * from pelanggan, invoice where invoice.id_pelanggan = pelanggan.id_pelanggan and 
        invoice.id_pelanggan='$pelanggan' and
        invoice.status='Selesai' and 
        invoice.status_bayar='Belum Lunas' and
        invoice.deleted_at is null order by invoice.tanggal asc");
        $pen = $this->db->query("select * from pelanggan where id_pelanggan='$pelanggan' ");
        if($pen->num_rows() > 0){
            foreach($pen->result() as $p){
                $data["pelanggan"] = $p->nama_pelanggan;
            }
        }
		    
		#Menampilkan halaman#
		$this->load->view("admin/laporan/laporan-piutang-tampil.php",$data);
        #Menampilkan halaman#
	}


    function laporan_buku_keuangan(){
       
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
        $this->load->view("admin/laporan/laporan-buku-keuangan.php");
        $this->load->view("admin/template/footer.php");

    }

    function laporan_buku_keuangan_tampil(){
		$dari = $this->input->post("dari");
		$sampai = $this->input->post("sampai");
        $nama_bulan = array(1=>"Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
		$data["lap"] = array(
					"dari"=>substr($dari,0,2)."-".$nama_bulan[(int)substr($dari,3,2)]."-".substr($dari,6,4),
					"sampai"=>substr($sampai,0,2)."-".$nama_bulan[(int)substr($sampai,3,2)]."-".substr($sampai,6,4));
        $this->load->model("transaksi_model");
		
		

		$data["gaji"] = $this->db->query("select * from transaksi where
        (transaksi.tanggal between '".tgl_pecah($dari)."' and '".tgl_pecah($sampai)."' ) and 
        transaksi.deleted_at is null order by transaksi.tanggal asc, transaksi.created_at asc");
        $t = $this->db->query("select sum(debit) as db, sum(kredit) as kr from transaksi where
        transaksi.tanggal < '".tgl_pecah($dari)."' and 
        transaksi.deleted_at is null ");

        $saldo_awal = 0;
        
        if($t->num_rows() > 0){
            foreach($t->result() as $r){
                $saldo_db = $r->db;
                $saldo_kr = $r->kr;
            }
            $saldo_awal = $saldo_db - $saldo_kr;
        }
        $data["saldo"] = $saldo_awal;
		
		    
		

		#Menampilkan halaman#
		$this->load->view("admin/laporan/laporan-buku-keuangan-tampil.php",$data);
        #Menampilkan halaman#
	}


    /* =====================================================================
     *  LAPORAN BIAYA INVOICE
     *  Route : laporan/laporan-biaya-invoice        (form filter)
     *          laporan/laporan-biaya-invoice-tampil (hasil, tab baru / siap cetak)
     * ===================================================================== */
    function laporan_biaya_invoice(){
        $level = $this->session->level;
        if($level == "Programmer")
        {
            $this->load->view("programmer/template/header.php");
            $this->load->view("programmer/template/menu.php");
        } else if($level == "Owner"){
            $this->load->view("owner/template/header.php");
            $this->load->view("owner/template/menu.php");
        } else {
            $this->load->view("admin/template/header.php");
            $this->load->view("admin/template/menu.php");
        }
        $data['kategori']  = $this->db->get('kategori')->result();
        $data['pelanggan'] = $this->db->query("select * from pelanggan where deleted_at is null order by nama_pelanggan asc");
        $this->load->view("admin/laporan/laporan-biaya-invoice.php", $data);
        $this->load->view("admin/template/footer.php");
    }

    function laporan_biaya_invoice_tampil(){
        $id_kategori  = (string) $this->input->post("id_kategori");
        $id_pelanggan = (string) $this->input->post("id_pelanggan");
        $jenis        = $this->input->post("jenis") === "Rinci" ? "Rinci" : "Ringkas";
        $status       = in_array($this->input->post("status"), array("Lunas", "Belum Lunas")) ? $this->input->post("status") : "Semua";
        $dari         = $this->_tgl_valid($this->input->post("dari"));
        $sampai       = $this->_tgl_valid($this->input->post("sampai"));

        if (!$dari || !$sampai) {
            show_error('Tanggal tidak valid. Gunakan format dd-mm-yyyy.', 400, 'Tanggal Tidak Valid');
            return;
        }
        if ($dari > $sampai) { $tmp = $dari; $dari = $sampai; $sampai = $tmp; }   // tukar jika terbalik

        $this->load->model("invoice_cost_model");
        $rows = $this->invoice_cost_model->laporan($dari, $sampai, $id_kategori === '' ? '*' : $id_kategori, $id_pelanggan === '' ? '*' : $id_pelanggan);

        // Kelompokkan baris biaya per invoice
        $invoices = array();
        foreach ($rows as $r) {
            $id = $r->id_invoice;
            if (!isset($invoices[$id])) {
                $invoices[$id] = array(
                    'id_invoice'     => $id,
                    'tanggal'        => $r->tanggal,
                    'nama_pelanggan' => $r->nama_pelanggan,
                    'no_polisi'      => $r->no_polisi,
                    'nama_kategori'  => $r->nama_kategori,
                    'status'         => $r->status,
                    'total_bill'     => 0,
                    'total_paid'     => 0,
                    'biaya'          => array(),
                );
            }
            $bill = (float) $r->bill;
            $paid = (float) $r->total_paid;
            $invoices[$id]['total_bill'] += $bill;
            $invoices[$id]['total_paid'] += $paid;
            $invoices[$id]['biaya'][] = array(
                'bill_name' => $r->bill_name,
                'bill'      => $bill,
                'paid'      => $paid,
                'sisa'      => $bill - $paid,
                'last_paid' => $r->last_paid,
            );
        }

        // Filter status pembayaran (level invoice: lunas jika seluruh biayanya terbayar)
        $grand = array('invoice' => 0, 'biaya' => 0, 'bill' => 0, 'paid' => 0, 'sisa' => 0, 'inv_lunas' => 0, 'inv_belum' => 0);
        foreach ($invoices as $id => $inv) {
            $sisa  = round($inv['total_bill'] - $inv['total_paid'], 2);
            $lunas = $sisa <= 0;
            if (($status == "Lunas" && !$lunas) || ($status == "Belum Lunas" && $lunas)) {
                unset($invoices[$id]);
                continue;
            }
            $invoices[$id]['sisa']  = $sisa;
            $invoices[$id]['lunas'] = $lunas;

            $grand['invoice']++;
            $grand['biaya'] += count($inv['biaya']);
            $grand['bill']  += $inv['total_bill'];
            $grand['paid']  += $inv['total_paid'];
            $grand['sisa']  += $sisa;
            $lunas ? $grand['inv_lunas']++ : $grand['inv_belum']++;
        }

        // Rekap per nama biaya (mis. total "Biaya Pengolahan" di periode ini)
        $per_nama = array();
        foreach ($invoices as $inv) {
            foreach ($inv['biaya'] as $b) {
                $key = mb_strtolower(trim($b['bill_name']));
                if (!isset($per_nama[$key])) {
                    $per_nama[$key] = array('bill_name' => trim($b['bill_name']), 'jumlah' => 0, 'bill' => 0, 'paid' => 0, 'sisa' => 0);
                }
                $per_nama[$key]['jumlah']++;
                $per_nama[$key]['bill'] += $b['bill'];
                $per_nama[$key]['paid'] += $b['paid'];
                $per_nama[$key]['sisa'] += $b['sisa'];
            }
        }
        uasort($per_nama, function ($a, $b) { return $b['bill'] <=> $a['bill']; });

        // Label filter untuk kop laporan
        $label_kategori = "Semua Kategori";
        if ($id_kategori !== '*' && $id_kategori !== '') {
            $k = $this->db->get_where('kategori', array('id' => $id_kategori))->row();
            if ($k) $label_kategori = $k->nama_kategori;
        }
        $label_pelanggan = "Semua Pelanggan";
        if ($id_pelanggan !== '*' && $id_pelanggan !== '') {
            $p = $this->db->get_where('pelanggan', array('id_pelanggan' => $id_pelanggan))->row();
            if ($p) $label_pelanggan = $p->nama_pelanggan;
        }

        $data = array(
            'invoices'  => $invoices,
            'grand'     => $grand,
            'per_nama'  => $per_nama,
            'jenis'     => $jenis,
            'status'    => $status,
            'periode'   => tgl_indo($dari) . " s/d " . tgl_indo($sampai),
            'kategori'  => $label_kategori,
            'pelanggan' => $label_pelanggan,
        );
        $this->load->view("admin/laporan/laporan-biaya-invoice-tampil.php", $data);
    }

    /** "01-10-2026" -> "2026-10-01" ; FALSE jika tidak valid */
    private function _tgl_valid($tgl){
        $tgl = trim((string) $tgl);
        $d = DateTime::createFromFormat('!d-m-Y', $tgl);
        return ($d && $d->format('d-m-Y') === $tgl) ? $d->format('Y-m-d') : FALSE;
    }

    function get_pengirim(){
        echo"<label for='ProductCode'>Pilih Pengirim</label>";
        echo"<select class='js-example-basic-multiple  form-control' name='pengirim[]' multiple='multiple' required>";
        echo "</select>";
    }

}
?>