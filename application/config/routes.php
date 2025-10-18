<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'login';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route["login-super"] = "login/login_super";
$route["maintenance"] = "maintenance";
$route["home"] = "home";


/* ROUTE SUPERADMIN */
$route["setting/langganan"] = "setting/langganan";
$route["setting/setting-backup"] = "setting/setting_backup";
$route["setting/setting-maintenance"] = "setting/setting_maintenance";

/* ROUTE ADMIN */
$route["data/pengirim"] = "data/pengirim";
$route["data/pelanggan"] = "data/pelanggan";
$route["data/karyawan"] = "data/karyawan";
$route["data/karyawan/cetak-absensi"] = "data/karyawan_cetak_absensi";

$route["transaksi/pembelian"] = "pembelian";
$route["transaksi/pembelian/tambah-timbangan/(:any)"] = "pembelian/tambah_timbangan/$1";
$route["transaksi/pembelian/cetak-nota/(:any)"] = "pembelian/cetak_nota/$1";
$route["transaksi/pembelian/riwayat-pembayaran/(:any)"] = "pembelian/riwayat_pembayaran/$1";

$route["transaksi/buku-keuangan"] = "bukukeuangan";
$route["transaksi/invoice"] = "invoice";
$route["transaksi/invoice/tambah-barang/(:any)"] = "invoice/tambah_barang/$1";
$route["transaksi/invoice/cetak-nota/(:any)"] = "invoice/cetak_nota/$1";
$route["transaksi/invoice/surat-jalan/(:any)"] = "invoice/surat_jalan/$1";
$route["transaksi/invoice/riwayat-pembayaran/(:any)"] = "invoice/riwayat_pembayaran/$1";
$route["transaksi/gaji-karyawan"] = "gaji";
$route["transaksi/gaji-karyawan/tambah-gaji/(:any)"] = "gaji/tambah_gaji/$1";
$route["transaksi/gaji-karyawan/cetak/(:any)"] = "gaji/cetak/$1";

$route["laporan/laporan-pembelian"] = "laporan/laporan_pembelian";
$route["laporan/laporan-pembelian-tampil"] = "laporan/laporan_pembelian_tampil";
$route["laporan/laporan-penjualan"] = "laporan/laporan_penjualan";
$route["laporan/laporan-penjualan-tampil"] = "laporan/laporan_penjualan_tampil";
$route["laporan/laporan-piutang"] = "laporan/laporan_piutang";
$route["laporan/laporan-piutang-tampil"] = "laporan/laporan_piutang_tampil";
$route["laporan/laporan-buku-keuangan"] = "laporan/laporan_buku_keuangan";
$route["laporan/laporan-buku-keuangan-tampil"] = "laporan/laporan_buku_keuangan_tampil";

$route["akun/ganti-password"] = "akun/ganti_password";
$route["akun/tentang-software"] = "akun/tentang_software";

