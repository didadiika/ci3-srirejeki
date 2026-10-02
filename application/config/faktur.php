<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Identitas Toko untuk Faktur / Nota PDF
|--------------------------------------------------------------------------
| Ubah nilai di bawah ini sesuai identitas usaha. Dipakai oleh
| Invoice::cetak_nota() saat membuat PDF faktur.
*/
$config['faktur_toko'] = array(
    'nama'     => 'MACTEL SRI REJEKI',
    'alamat'   => 'Mejobo',
    'kota'     => 'Kudus',
    'telepon'  => '085 325 019 101',
);

// Judul dokumen di kotak kanan atas
$config['faktur_judul'] = 'INVOICE';

// Prefix nomor faktur bila tabel invoice belum punya kolom nomor
$config['faktur_prefix'] = 'INV';

// Ukuran kertas: 'A4', 'letter', 'legal', atau array(0, 0, lebar_pt, tinggi_pt)
$config['faktur_kertas']   = 'A4';
$config['faktur_orientasi'] = 'portrait';

// Label tanda tangan
$config['faktur_ttd'] = array('Disiapkan Oleh', 'Dikirim Oleh', 'Diterima Oleh');

// Catatan kaki faktur
$config['faktur_catatan'] = 'Barang yang sudah dibeli tidak dapat ditukar/dikembalikan tanpa persetujuan sebelumnya. Terima kasih.';

/*
|--------------------------------------------------------------------------
| Surat Jalan PDF  (Invoice::surat_jalan)
|--------------------------------------------------------------------------
*/
$config['sj_judul']  = 'SURAT JALAN';
$config['sj_prefix'] = 'SJ';
// Satuan qty yang ditampilkan di kolom Qty & total. Kosongkan ('') bila tidak perlu.
$config['sj_satuan'] = 'Kg';
$config['sj_ttd']    = array('Disiapkan Oleh', 'Supir / Pengirim', 'Diterima Oleh');

/*
|--------------------------------------------------------------------------
| Rekap Timbangan Bulog PDF  (Bulog::cetak)
|--------------------------------------------------------------------------
*/
$config['bulog_judul']  = 'REKAP TIMBANGAN';
$config['bulog_prefix'] = 'BLG';
// Jumlah kolom grid rincian timbangan per baris
$config['bulog_kolom']  = 10;
$config['bulog_ttd']    = array('Penimbang', 'Sopir / Pengirim', 'Mengetahui');
