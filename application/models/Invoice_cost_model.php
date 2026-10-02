<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model Biaya Invoice
 * Tabel : invoice_costs         (id, invoice_id, bill_name, bill)
 *         invoice_cost_payments (id, invoice_cost_id, paid, date_of_paid)
 */
class Invoice_cost_model extends CI_Model{

    /* ======================= INVOICE ======================= */

    function get_invoice($invoice_id){
        return $this->db->query("select invoice.*, pelanggan.nama_pelanggan, pelanggan.alamat, pelanggan.telepon
            from invoice
            inner join pelanggan on pelanggan.id_pelanggan = invoice.id_pelanggan
            where invoice.id_invoice = ? and invoice.deleted_at is null", array($invoice_id))->row();
    }

    /* ======================= BIAYA ======================= */

    /**
     * Daftar biaya per invoice lengkap dengan akumulasi pembayaran.
     */
    function get_costs($invoice_id){
        return $this->db->query("select c.id, c.invoice_id, c.bill_name, c.bill,
                coalesce(p.total_paid, 0) as total_paid,
                coalesce(p.jumlah_bayar, 0) as jumlah_bayar
            from invoice_costs c
            left join (
                select invoice_cost_id, sum(paid) as total_paid, count(*) as jumlah_bayar
                from invoice_cost_payments group by invoice_cost_id
            ) p on p.invoice_cost_id = c.id
            where c.invoice_id = ?
            order by c.bill_name asc", array($invoice_id))->result();
    }

    function get_cost($id){
        return $this->db->query("select c.*,
                (select coalesce(sum(paid),0) from invoice_cost_payments where invoice_cost_id = c.id) as total_paid,
                (select count(*) from invoice_cost_payments where invoice_cost_id = c.id) as jumlah_bayar
            from invoice_costs c where c.id = ?", array($id))->row();
    }

    function insert_cost($data){
        return $this->db->insert('invoice_costs', $data);
    }

    function update_cost($id, $data){
        $this->db->where('id', $id);
        return $this->db->update('invoice_costs', $data);
    }

    function delete_cost($id){
        $this->db->where('id', $id);
        return $this->db->delete('invoice_costs');
    }

    /**
     * Total biaya & total terbayar seluruh biaya pada satu invoice.
     */
    function get_summary($invoice_id){
        $r = $this->db->query("select
                coalesce(sum(c.bill),0) as total_bill,
                coalesce((select sum(p.paid) from invoice_cost_payments p
                          inner join invoice_costs c2 on c2.id = p.invoice_cost_id
                          where c2.invoice_id = ?),0) as total_paid
            from invoice_costs c where c.invoice_id = ?", array($invoice_id, $invoice_id))->row();
        return $r;
    }

    /* ======================= PEMBAYARAN ======================= */

    function get_payments($cost_id){
        return $this->db->query("select * from invoice_cost_payments
            where invoice_cost_id = ? order by date_of_paid asc, id asc", array($cost_id))->result();
    }

    function get_payment($id){
        return $this->db->query("select * from invoice_cost_payments where id = ?", array($id))->row();
    }

    /**
     * Total pembayaran sebuah biaya, opsional mengecualikan satu pembayaran (untuk validasi edit).
     */
    function total_paid($cost_id, $exclude_payment_id = NULL){
        if ($exclude_payment_id) {
            $r = $this->db->query("select coalesce(sum(paid),0) as t from invoice_cost_payments
                where invoice_cost_id = ? and id <> ?", array($cost_id, $exclude_payment_id))->row();
        } else {
            $r = $this->db->query("select coalesce(sum(paid),0) as t from invoice_cost_payments
                where invoice_cost_id = ?", array($cost_id))->row();
        }
        return (float) $r->t;
    }

    function insert_payment($data){
        return $this->db->insert('invoice_cost_payments', $data);
    }

    function update_payment($id, $data){
        $this->db->where('id', $id);
        return $this->db->update('invoice_cost_payments', $data);
    }

    function delete_payment($id){
        $this->db->where('id', $id);
        return $this->db->delete('invoice_cost_payments');
    }
}
