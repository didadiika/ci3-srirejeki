<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once (dirname(__FILE__) . "/BaseController.php");

/**
 * Biaya Invoice & Pembayaran Biaya Invoice
 *
 * Halaman : transaksi/invoice/biaya/{id_invoice}
 * AJAX    : invoice_cost/data/{id_invoice}           (GET  - daftar biaya + ringkasan)
 *           invoice_cost/simpan_biaya                (POST - tambah / edit biaya)
 *           invoice_cost/hapus_biaya                 (POST - hapus biaya, hanya jika belum ada pembayaran)
 *           invoice_cost/pembayaran/{id_biaya}       (GET  - daftar pembayaran sebuah biaya)
 *           invoice_cost/simpan_pembayaran           (POST - tambah / edit pembayaran)
 *           invoice_cost/hapus_pembayaran            (POST - hapus pembayaran)
 *
 * Semua endpoint AJAX mengembalikan JSON: {status: true|false, message: "...", ...}
 */
class Invoice_cost extends BaseController{

    function __construct(){
        parent::__construct();

        $cek_login = $this->session->userdata('authenticated');
        if ($cek_login != true) {
            if ($this->input->is_ajax_request()) {
                $this->_json(array('status' => false, 'message' => 'Sesi login berakhir, silakan login ulang.'), 401);
                exit;
            }
            redirect(base_url('login'));
        }

        $this->load->model('invoice_cost_model');
    }

    /* ============================ HALAMAN ============================ */

    function index($invoice_id = NULL){
        $invoice = $invoice_id ? $this->invoice_cost_model->get_invoice($invoice_id) : NULL;
        if (!$invoice) {
            show_error('Invoice tidak ditemukan atau sudah dihapus.', 404, 'Invoice Tidak Ditemukan');
            return;
        }

        $this->_header();
        $this->load->view('admin/transaksi/invoice-biaya.php', array('invoice' => $invoice));
        $this->load->view('admin/template/footer.php');
    }

    /* ============================ BIAYA ============================ */

    function data($invoice_id = NULL){
        $invoice = $invoice_id ? $this->invoice_cost_model->get_invoice($invoice_id) : NULL;
        if (!$invoice) {
            return $this->_json(array('status' => false, 'message' => 'Invoice tidak ditemukan.'), 404);
        }

        $costs = array();
        foreach ($this->invoice_cost_model->get_costs($invoice_id) as $c) {
            $bill = (float) $c->bill;
            $paid = (float) $c->total_paid;
            $costs[] = array(
                'id'           => $c->id,
                'bill_name'    => $c->bill_name,
                'bill'         => $bill,
                'total_paid'   => $paid,
                'sisa'         => round($bill - $paid, 2),
                'jumlah_bayar' => (int) $c->jumlah_bayar,
                'lunas'        => $this->_cent($paid) >= $this->_cent($bill),
                'bisa_hapus'   => ((int) $c->jumlah_bayar) === 0,
            );
        }

        $s = $this->invoice_cost_model->get_summary($invoice_id);
        return $this->_json(array(
            'status'  => true,
            'data'    => $costs,
            'summary' => array(
                'total_bill' => (float) $s->total_bill,
                'total_paid' => (float) $s->total_paid,
                'sisa'       => round((float) $s->total_bill - (float) $s->total_paid, 2),
            ),
        ));
    }

    function simpan_biaya(){
        $id         = trim((string) $this->input->post('id'));
        $invoice_id = trim((string) $this->input->post('invoice_id'));
        $bill_name  = trim((string) $this->input->post('bill_name'));
        $bill       = $this->_parse_uang($this->input->post('bill'));

        if (!$this->invoice_cost_model->get_invoice($invoice_id)) {
            return $this->_json(array('status' => false, 'message' => 'Invoice tidak ditemukan.'));
        }
        if ($bill_name === '') {
            return $this->_json(array('status' => false, 'message' => 'Nama biaya wajib diisi.'));
        }
        if (mb_strlen($bill_name) > 255) {
            return $this->_json(array('status' => false, 'message' => 'Nama biaya maksimal 255 karakter.'));
        }
        if ($bill === NULL || $bill <= 0) {
            return $this->_json(array('status' => false, 'message' => 'Nominal biaya harus lebih dari 0.'));
        }

        $data = array('bill_name' => $bill_name, 'bill' => $bill);

        if ($id === '') {
            // ---- Tambah ----
            $data['id']         = id_primary();
            $data['invoice_id'] = $invoice_id;
            $this->invoice_cost_model->insert_cost($data);
            return $this->_json(array('status' => true, 'message' => 'Biaya berhasil ditambahkan.'));
        }

        // ---- Edit ----
        $cost = $this->invoice_cost_model->get_cost($id);
        if (!$cost || $cost->invoice_id !== $invoice_id) {
            return $this->_json(array('status' => false, 'message' => 'Data biaya tidak ditemukan.'));
        }
        if ($this->_cent($bill) < $this->_cent($cost->total_paid)) {
            return $this->_json(array('status' => false,
                'message' => 'Nominal biaya tidak boleh lebih kecil dari total yang sudah dibayar (Rp ' . uang($cost->total_paid) . ').'));
        }
        $this->invoice_cost_model->update_cost($id, $data);
        return $this->_json(array('status' => true, 'message' => 'Biaya berhasil diperbarui.'));
    }

    function hapus_biaya(){
        $id   = trim((string) $this->input->post('id'));
        $cost = $this->invoice_cost_model->get_cost($id);

        if (!$cost) {
            return $this->_json(array('status' => false, 'message' => 'Data biaya tidak ditemukan.'));
        }
        if ((int) $cost->jumlah_bayar > 0) {
            return $this->_json(array('status' => false,
                'message' => 'Biaya "' . $cost->bill_name . '" tidak dapat dihapus karena sudah ada pembayaran. Hapus pembayarannya terlebih dahulu.'));
        }

        $this->invoice_cost_model->delete_cost($id);
        return $this->_json(array('status' => true, 'message' => 'Biaya berhasil dihapus.'));
    }

    /* ============================ PEMBAYARAN ============================ */

    function pembayaran($cost_id = NULL){
        $cost = $cost_id ? $this->invoice_cost_model->get_cost($cost_id) : NULL;
        if (!$cost) {
            return $this->_json(array('status' => false, 'message' => 'Data biaya tidak ditemukan.'), 404);
        }

        $rows = array();
        foreach ($this->invoice_cost_model->get_payments($cost_id) as $p) {
            $rows[] = array(
                'id'           => $p->id,
                'paid'         => (float) $p->paid,
                'date_of_paid' => $p->date_of_paid,                         // Y-m-d
                'tanggal'      => $p->date_of_paid ? tgl_db($p->date_of_paid) : '',   // d-m-Y (untuk form)
                'tanggal_indo' => $p->date_of_paid ? tgl_indo($p->date_of_paid) : '-',
            );
        }

        return $this->_json(array(
            'status' => true,
            'cost'   => array(
                'id'         => $cost->id,
                'bill_name'  => $cost->bill_name,
                'bill'       => (float) $cost->bill,
                'total_paid' => (float) $cost->total_paid,
                'sisa'       => round((float) $cost->bill - (float) $cost->total_paid, 2),
            ),
            'data'   => $rows,
        ));
    }

    function simpan_pembayaran(){
        $id      = trim((string) $this->input->post('id'));
        $cost_id = trim((string) $this->input->post('invoice_cost_id'));
        $paid    = $this->_parse_uang($this->input->post('paid'));
        $tanggal = $this->_parse_tanggal($this->input->post('date_of_paid'));

        $cost = $this->invoice_cost_model->get_cost($cost_id);
        if (!$cost) {
            return $this->_json(array('status' => false, 'message' => 'Data biaya tidak ditemukan.'));
        }
        if ($tanggal === NULL) {
            return $this->_json(array('status' => false, 'message' => 'Tanggal pembayaran tidak valid (format dd-mm-yyyy).'));
        }
        if ($tanggal > date('Y-m-d')) {
            return $this->_json(array('status' => false, 'message' => 'Tanggal pembayaran tidak boleh melebihi hari ini.'));
        }
        if ($paid === NULL || $paid <= 0) {
            return $this->_json(array('status' => false, 'message' => 'Nominal pembayaran harus lebih dari 0.'));
        }

        if ($id !== '') {
            $payment = $this->invoice_cost_model->get_payment($id);
            if (!$payment || $payment->invoice_cost_id !== $cost_id) {
                return $this->_json(array('status' => false, 'message' => 'Data pembayaran tidak ditemukan.'));
            }
        }

        // Validasi: total pembayaran tidak boleh melebihi nominal biaya
        $sudah_bayar = $this->invoice_cost_model->total_paid($cost_id, $id !== '' ? $id : NULL);
        $maks        = (float) $cost->bill - $sudah_bayar;
        if ($this->_cent($paid) > $this->_cent($maks)) {
            return $this->_json(array('status' => false,
                'message' => 'Nominal pembayaran melebihi sisa tagihan. Maksimal Rp ' . uang($maks) . '.'));
        }

        $data = array('paid' => $paid, 'date_of_paid' => $tanggal);

        if ($id === '') {
            $data['id']              = id_primary();
            $data['invoice_cost_id'] = $cost_id;
            $this->invoice_cost_model->insert_payment($data);
            return $this->_json(array('status' => true, 'message' => 'Pembayaran berhasil disimpan.'));
        }

        $this->invoice_cost_model->update_payment($id, $data);
        return $this->_json(array('status' => true, 'message' => 'Pembayaran berhasil diperbarui.'));
    }

    function hapus_pembayaran(){
        $id      = trim((string) $this->input->post('id'));
        $payment = $this->invoice_cost_model->get_payment($id);

        if (!$payment) {
            return $this->_json(array('status' => false, 'message' => 'Data pembayaran tidak ditemukan.'));
        }

        $this->invoice_cost_model->delete_payment($id);
        return $this->_json(array('status' => true, 'message' => 'Pembayaran berhasil dihapus.'));
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

    private function _json($data, $code = 200){
        $this->output
            ->set_status_header($code)
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode($data))
            ->_display();
        exit;
    }

    /**
     * "20.000.000" / "20.000.000,50" / "20000000" -> 20000000.50 (float, 2 desimal) ; NULL jika tidak valid
     */
    private function _parse_uang($nilai){
        $nilai = trim((string) $nilai);
        if ($nilai === '') return NULL;
        $nilai = uangPecahDecimal($nilai);
        if (!is_numeric($nilai)) return NULL;
        $nilai = round((float) $nilai, 2);
        if ($nilai > 9999999999999.99) return NULL;   // batas decimal(15,2)
        return $nilai;
    }

    /**
     * "01-10-2026" -> "2026-10-01" ; NULL jika tidak valid
     */
    private function _parse_tanggal($tgl){
        $tgl = trim((string) $tgl);
        $d   = DateTime::createFromFormat('!d-m-Y', $tgl);
        if (!$d || $d->format('d-m-Y') !== $tgl) return NULL;
        return $d->format('Y-m-d');
    }

    /** Konversi ke satuan sen (integer) supaya perbandingan nominal bebas error floating point */
    private function _cent($nilai){
        return (int) round(((float) $nilai) * 100);
    }
}
