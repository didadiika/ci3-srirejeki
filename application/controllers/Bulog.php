<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once (dirname(__FILE__) . "/BaseController.php");

/**
 * Transaksi > Bulog  (barang transit: hanya ditimbang, tidak dibeli)
 *
 * Halaman : transaksi/bulog                 -> daftar bulog
 *           transaksi/bulog/detail/{id}     -> truk & timbangan dalam satu bulog
 *
 * Cetak   : transaksi/bulog/cetak/{id}     -> PDF rekap timbangan (Dompdf)
 *
 * AJAX (JSON {status, message, ...}):
 *   bulog/tampil                 GET   datatables daftar bulog
 *   bulog/simpan                 POST  tambah / edit bulog (tanggal)
 *   bulog/hapus                  POST  soft delete bulog (isi deleted_at)
 *   bulog/data/{id}              GET   detail bulog + truk + timbangan
 *   bulog/simpan_truk            POST  tambah / edit truk
 *   bulog/hapus_truk             POST  hapus truk (hanya jika belum ada timbangan)
 *   bulog/simpan_timbangan       POST  tambah / edit timbangan
 *   bulog/hapus_timbangan        POST  hapus timbangan
 */
class Bulog extends BaseController{

    const MAKS_TIMBANG = 999999;   // batas wajar 1x timbang (kg)

    function __construct(){
        parent::__construct();

        if ($this->session->userdata('authenticated') != true) {
            if ($this->input->is_ajax_request()) {
                $this->_json(array('status' => false, 'message' => 'Sesi login berakhir, silakan login ulang.'), 401);
            }
            redirect(base_url('login'));
        }
        $this->load->model('bulog_model');
    }

    /* ============================ HALAMAN ============================ */

    function index(){
        $this->_header();
        $this->load->view('admin/transaksi/bulog.php');
        $this->load->view('admin/template/footer.php');
    }

    function detail($id = NULL){
        $bulog = $id ? $this->bulog_model->get_bulog($id) : NULL;
        if (!$bulog) {
            show_error('Data Bulog tidak ditemukan atau sudah dihapus.', 404, 'Bulog Tidak Ditemukan');
            return;
        }
        $this->_header();
        $this->load->view('admin/transaksi/bulog-detail.php', array('bulog' => $bulog));
        $this->load->view('admin/template/footer.php');
    }

    /**
     * Cetak rekap timbangan Bulog sebagai PDF (stream inline ke browser).
     * Route : transaksi/bulog/cetak/{id}
     * Opsi  : ?download=1 -> paksa unduh file ; ?html=1 -> tampilkan HTML mentah (cek layout)
     */
    function cetak($id = NULL){
        $bulog = $id ? $this->bulog_model->get_bulog($id) : NULL;
        if (!$bulog) {
            show_error('Data Bulog tidak ditemukan atau sudah dihapus.', 404, 'Bulog Tidak Ditemukan');
            return;
        }

        // Timbangan dikelompokkan per truk (urut created_at ASC)
        $per_truk = array();
        foreach ($this->bulog_model->get_weights_by_bulog($id) as $w) {
            $per_truk[$w->bulog_order_id][] = $w;
        }
        $trucks = array();
        foreach ($this->bulog_model->get_orders($id) as $o) {
            $o->weights = isset($per_truk[$o->id]) ? $per_truk[$o->id] : array();
            $trucks[] = $o;
        }

        $this->config->load('faktur');
        $nomor = $this->config->item('bulog_prefix') . '-' . date('ymd', strtotime($bulog->date))
               . '-' . strtoupper(substr(str_replace('-', '', $bulog->id), 0, 6));

        $data = array(
            'bulog'   => $bulog,
            'trucks'  => $trucks,
            'nomor'   => $nomor,
            'toko'    => $this->config->item('faktur_toko'),
            'judul'   => $this->config->item('bulog_judul'),
            'kolom'   => max(1, (int) $this->config->item('bulog_kolom')),
            'ttd'     => $this->config->item('bulog_ttd'),
            'petugas' => $this->session->userdata('username'),
        );

        $html = $this->load->view('admin/transaksi/bulog-cetak-pdf.php', $data, TRUE);
        if ($this->input->get('html')) {
            echo $html;
            return;
        }

        $nama_file = 'Bulog-' . $nomor . '.pdf';
        $this->_stream_pdf($html, $data['judul'] . ' ' . $nomor, $data['toko']['nama'], $nama_file);
    }

    /* ============================ BULOG ============================ */

    function tampil(){
        $rows  = $this->bulog_model->make_datatables();
        $start = isset($_GET['start']) ? (int) $_GET['start'] : 0;
        $no    = $start + 1;
        $data  = array();

        foreach ($rows as $r) {
            $url = base_url('transaksi/bulog/detail/' . $r->id);
            $data[] = array(
                $no++,
                tgl_db($r->date),
                '<span class="badge bg-blue">' . (int) $r->jumlah_truk . ' truk</span>',
                '<div class="text-right"><strong>' . uang($r->total_weight) . '</strong> kg</div>',
                '<div class="text-center" style="white-space:nowrap">
                    <a href="' . $url . '" class="btn btn-xs btn-primary"><i class="fa fa-balance-scale"></i> Timbang</a>
                    <a href="' . base_url('transaksi/bulog/cetak/' . $r->id) . '" target="_blank" class="btn btn-xs btn-default"><i class="fa fa-print"></i> Cetak</a>
                    <button type="button" class="btn btn-xs btn-warning item_edit" data-id="' . $r->id . '" data-tanggal="' . tgl_db($r->date) . '"><i class="fa fa-pencil"></i> Edit</button>
                    <button type="button" class="btn btn-xs btn-danger item_hapus" data-id="' . $r->id . '" data-tanggal="' . tgl_indo($r->date) . '" data-truk="' . (int) $r->jumlah_truk . '"><i class="fa fa-trash"></i> Hapus</button>
                </div>',
            );
        }

        $this->_json(array(
            'draw'            => isset($_GET['draw']) ? (int) $_GET['draw'] : 0,
            'recordsTotal'    => $this->bulog_model->get_all_data(),
            'recordsFiltered' => $this->bulog_model->get_filtered_data(),
            'data'            => $data,
        ));
    }

    function simpan(){
        $id      = trim((string) $this->input->post('id'));
        $tanggal = $this->_parse_tanggal($this->input->post('date'));

        if (!$tanggal) {
            $this->_json(array('status' => false, 'message' => 'Tanggal tidak valid (format dd-mm-yyyy).'));
        }

        if ($id === '') {
            $new_id = id_primary();
            $this->bulog_model->insert_bulog(array(
                'id'           => $new_id,
                'date'         => $tanggal,
                'total_weight' => 0,
                'created_at'   => date('Y-m-d H:i:s'),
            ));
            $this->_json(array('status' => true, 'message' => 'Bulog berhasil ditambahkan.',
                'id' => $new_id, 'url' => base_url('transaksi/bulog/detail/' . $new_id)));
        }

        if (!$this->bulog_model->get_bulog($id)) {
            $this->_json(array('status' => false, 'message' => 'Data Bulog tidak ditemukan.'));
        }
        $this->bulog_model->update_bulog($id, array('date' => $tanggal, 'updated_at' => date('Y-m-d H:i:s')));
        $this->_json(array('status' => true, 'message' => 'Tanggal Bulog berhasil diperbarui.'));
    }

    function hapus(){
        $id = trim((string) $this->input->post('id'));
        if (!$this->bulog_model->get_bulog($id)) {
            $this->_json(array('status' => false, 'message' => 'Data Bulog tidak ditemukan.'));
        }
        $this->bulog_model->soft_delete_bulog($id);
        $this->_json(array('status' => true, 'message' => 'Bulog berhasil dihapus.'));
    }

    /* ============================ DETAIL (truk + timbangan) ============================ */

    function data($id = NULL){
        $bulog = $id ? $this->bulog_model->get_bulog($id) : NULL;
        if (!$bulog) {
            $this->_json(array('status' => false, 'message' => 'Data Bulog tidak ditemukan.'), 404);
        }

        // Kelompokkan timbangan per truk (sudah urut created_at ASC)
        $per_truk = array();
        foreach ($this->bulog_model->get_weights_by_bulog($id) as $w) {
            $per_truk[$w->bulog_order_id][] = array(
                'id'         => $w->id,
                'weight'     => (int) $w->weight,
                'created_at' => $w->created_at,
                'jam'        => date('d-m-Y H:i:s', strtotime($w->created_at)),
            );
        }

        $orders = array();
        foreach ($this->bulog_model->get_orders($id) as $o) {
            $orders[] = array(
                'id'              => $o->id,
                'name'            => $o->name,
                'truck_number'    => $o->truck_number,
                'city'            => $o->city,
                'weight_by_truck' => (int) $o->weight_by_truck,
                'jumlah_timbang'  => (int) $o->jumlah_timbang,
                'bisa_hapus'      => ((int) $o->jumlah_timbang) === 0,
                'weights'         => isset($per_truk[$o->id]) ? $per_truk[$o->id] : array(),
            );
        }

        $this->_json(array(
            'status' => true,
            'bulog'  => array(
                'id'             => $bulog->id,
                'date'           => tgl_db($bulog->date),
                'date_indo'      => tgl_indo($bulog->date),
                'total_weight'   => (int) $bulog->total_weight,
                'jumlah_truk'    => (int) $bulog->jumlah_truk,
                'jumlah_timbang' => (int) $bulog->jumlah_timbang,
            ),
            'orders' => $orders,
        ));
    }

    /* ---------- Truk ---------- */

    function simpan_truk(){
        $id       = trim((string) $this->input->post('id'));
        $bulog_id = trim((string) $this->input->post('bulog_id'));
        $truk     = strtoupper(preg_replace('/\s+/', ' ', trim((string) $this->input->post('truck_number'))));
        $kota     = preg_replace('/\s+/', ' ', trim((string) $this->input->post('city')));
        $nama     = preg_replace('/\s+/', ' ', trim((string) $this->input->post('name')));

        if (!$this->bulog_model->get_bulog($bulog_id)) {
            $this->_json(array('status' => false, 'message' => 'Data Bulog tidak ditemukan.'));
        }
        if ($truk === '') {
            $this->_json(array('status' => false, 'message' => 'No truk wajib diisi.'));
        }
        if ($kota === '') {
            $this->_json(array('status' => false, 'message' => 'Kota wajib diisi.'));
        }
        if (mb_strlen($truk) > 30 || mb_strlen($kota) > 100 || mb_strlen($nama) > 100) {
            $this->_json(array('status' => false, 'message' => 'No truk maks. 30 karakter, nama & kota maks. 100 karakter.'));
        }

        // name opsional: kosong disimpan sebagai NULL
        $data = array('name' => ($nama === '' ? NULL : $nama), 'truck_number' => $truk, 'city' => $kota);

        if ($id === '') {
            $data['id']              = id_primary();
            $data['bulog_id']        = $bulog_id;
            $data['weight_by_truck'] = 0;
            $this->bulog_model->insert_order($data);
            $this->_json(array('status' => true, 'message' => 'Truk ' . $truk . ' berhasil ditambahkan.', 'id' => $data['id']));
        }

        $order = $this->bulog_model->get_order($id);
        if (!$order || $order->bulog_id !== $bulog_id) {
            $this->_json(array('status' => false, 'message' => 'Data truk tidak ditemukan.'));
        }
        $this->bulog_model->update_order($id, $data);
        $this->_json(array('status' => true, 'message' => 'Data truk berhasil diperbarui.'));
    }

    function hapus_truk(){
        $id    = trim((string) $this->input->post('id'));
        $order = $this->bulog_model->get_order($id);

        if (!$order) {
            $this->_json(array('status' => false, 'message' => 'Data truk tidak ditemukan.'));
        }
        if ((int) $order->jumlah_timbang > 0) {
            $this->_json(array('status' => false,
                'message' => 'Truk ' . $order->truck_number . ' tidak dapat dihapus karena sudah memiliki ' . $order->jumlah_timbang . ' data timbangan. Hapus timbangannya terlebih dahulu.'));
        }

        $this->bulog_model->delete_order($id);
        $this->bulog_model->recalc_bulog($order->bulog_id);
        $this->_json(array('status' => true, 'message' => 'Truk ' . $order->truck_number . ' berhasil dihapus.'));
    }

    /* ---------- Timbangan ---------- */

    function simpan_timbangan(){
        $id       = trim((string) $this->input->post('id'));
        $order_id = trim((string) $this->input->post('bulog_order_id'));
        $berat    = $this->_parse_berat($this->input->post('weight'));

        $order = $this->bulog_model->get_order($order_id);
        if (!$order) {
            $this->_json(array('status' => false, 'message' => 'Data truk tidak ditemukan.'));
        }
        if ($berat === NULL) {
            $this->_json(array('status' => false, 'message' => 'Berat harus angka bulat 1 s/d ' . uang(self::MAKS_TIMBANG) . ' kg.'));
        }

        if ($id === '') {
            $this->bulog_model->insert_weight($order_id, $berat);
            $this->bulog_model->recalc_order($order_id);
            $this->_json(array('status' => true, 'message' => uang($berat) . ' kg ditambahkan ke truk ' . $order->truck_number . '.'));
        }

        $w = $this->bulog_model->get_weight($id);
        if (!$w || $w->bulog_order_id !== $order_id) {
            $this->_json(array('status' => false, 'message' => 'Data timbangan tidak ditemukan.'));
        }
        $this->bulog_model->update_weight($id, $berat);
        $this->bulog_model->recalc_order($order_id);
        $this->_json(array('status' => true, 'message' => 'Timbangan diperbarui: ' . uang($w->weight) . ' → ' . uang($berat) . ' kg.'));
    }

    function hapus_timbangan(){
        $id = trim((string) $this->input->post('id'));
        $w  = $this->bulog_model->get_weight($id);
        if (!$w) {
            $this->_json(array('status' => false, 'message' => 'Data timbangan tidak ditemukan.'));
        }
        $this->bulog_model->delete_weight($id);
        $this->bulog_model->recalc_order($w->bulog_order_id);
        $this->_json(array('status' => true, 'message' => 'Timbangan ' . uang($w->weight) . ' kg dihapus.'));
    }

    /* ============================ HELPER (private) ============================ */

    private function _header(){
        $level = $this->session->level;
        if ($level == "Programmer") {
            $this->load->view("programmer/template/header.php");
            $this->load->view("programmer/template/menu.php");
        } else if ($level == "Owner") {
            $this->load->view("owner/template/header.php");
            $this->load->view("owner/template/menu.php");
        } else {
            $this->load->view("admin/template/header.php");
            $this->load->view("admin/template/menu.php");
        }
    }

    /** Render HTML -> PDF dengan Dompdf lalu stream ke browser (sama seperti Invoice). */
    private function _stream_pdf($html, $judul, $author, $nama_file){
        $options = new \Dompdf\Options();
        $options->set('isRemoteEnabled', false);
        $options->set('isHtml5ParserEnabled', true);
        $options->set('defaultFont', 'Helvetica');
        $options->set('chroot', FCPATH);

        $dompdf = new \Dompdf\Dompdf($options);
        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->setPaper($this->config->item('faktur_kertas'), 'portrait');
        $dompdf->addInfo('Title', $judul);
        $dompdf->addInfo('Author', $author);
        $dompdf->render();

        $canvas = $dompdf->getCanvas();
        $font   = $dompdf->getFontMetrics()->getFont('Helvetica', 'normal');
        $canvas->page_text($canvas->get_width() - 110, $canvas->get_height() - 26,
            'Halaman {PAGE_NUM} dari {PAGE_COUNT}', $font, 7, array(0.3, 0.3, 0.3));

        $dompdf->stream($nama_file, array('Attachment' => (bool) $this->input->get('download')));
        exit;
    }

    private function _json($data, $code = 200){
        $this->output
            ->set_status_header($code)
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode($data))
            ->_display();
        exit;
    }

    /** "01-10-2026" -> "2026-10-01" ; FALSE jika tidak valid */
    private function _parse_tanggal($tgl){
        $tgl = trim((string) $tgl);
        $d   = DateTime::createFromFormat('!d-m-Y', $tgl);
        return ($d && $d->format('d-m-Y') === $tgl) ? $d->format('Y-m-d') : FALSE;
    }

    /** "2.000" / "2000" -> 2000 ; NULL jika bukan bilangan bulat 1..MAKS */
    private function _parse_berat($v){
        $v = str_replace(array('.', ' '), '', trim((string) $v));
        if (!preg_match('/^\d+$/', $v)) return NULL;
        $v = (int) $v;
        return ($v >= 1 && $v <= self::MAKS_TIMBANG) ? $v : NULL;
    }
}
