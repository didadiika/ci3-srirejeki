<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model Bulog (barang transit: hanya ditimbang, tidak dibeli)
 *
 * bulogs              : id, date, total_weight, created_at, updated_at, deleted_at  (soft delete)
 * bulog_orders        : id, bulog_id, name, truck_number, city, weight_by_truck      (1 baris = 1 truk)
 * bulog_order_weights : id, bulog_order_id, weight, created_at                      (1 baris = 1x timbang)
 *
 * total_weight & weight_by_truck adalah rangkuman yang selalu dihitung ulang lewat recalc_*().
 */
class Bulog_model extends CI_Model{

    /* ======================= DATATABLES (daftar bulog) ======================= */

    var $order_kolom = array(NULL, 'b.date', 'jumlah_truk', 'b.total_weight', NULL);

    private function _query_list(){
        $this->db->select('b.id, b.date, b.total_weight, b.created_at,
            (select count(*) from bulog_orders o where o.bulog_id = b.id) as jumlah_truk', FALSE);
        $this->db->from('bulogs b');
        $this->db->where('b.deleted_at IS NULL', NULL, FALSE);

        $cari = isset($_GET['search']['value']) ? trim($_GET['search']['value']) : '';
        if ($cari !== '') {
            // dukung cari tanggal dd-mm-yyyy / sebagian, berat, no truk & kota
            $tgl = preg_match('/^(\d{1,2})-(\d{1,2})-(\d{4})$/', $cari, $m) ? sprintf('%04d-%02d-%02d', $m[3], $m[2], $m[1]) : $cari;
            $this->db->group_start();
            $this->db->like('b.date', $tgl);
            $this->db->or_like('b.total_weight', str_replace('.', '', $cari));
            $q = $this->db->escape('%' . $cari . '%');
            $this->db->or_where("b.id in (select bulog_id from bulog_orders where truck_number like $q or city like $q or name like $q)", NULL, FALSE);
            $this->db->group_end();
        }

        if (isset($_GET['order'][0]['column']) && !empty($this->order_kolom[(int) $_GET['order'][0]['column']])) {
            $dir = strtolower($_GET['order'][0]['dir']) === 'asc' ? 'asc' : 'desc';
            $this->db->order_by($this->order_kolom[(int) $_GET['order'][0]['column']], $dir);
        } else {
            $this->db->order_by('b.date', 'desc');
            $this->db->order_by('b.created_at', 'desc');
        }
    }

    function make_datatables(){
        $this->_query_list();
        if (isset($_GET['length']) && $_GET['length'] != -1) {
            $this->db->limit((int) $_GET['length'], (int) $_GET['start']);
        }
        return $this->db->get()->result();
    }

    function get_filtered_data(){
        $this->_query_list();
        return $this->db->get()->num_rows();
    }

    function get_all_data(){
        return $this->db->where('deleted_at IS NULL', NULL, FALSE)->count_all_results('bulogs');
    }

    /* ======================= BULOG ======================= */

    function get_bulog($id){
        return $this->db->query("select b.*,
                (select count(*) from bulog_orders o where o.bulog_id = b.id) as jumlah_truk,
                (select count(*) from bulog_order_weights w inner join bulog_orders o on o.id = w.bulog_order_id where o.bulog_id = b.id) as jumlah_timbang
            from bulogs b where b.id = ? and b.deleted_at is null", array($id))->row();
    }

    function insert_bulog($data){ return $this->db->insert('bulogs', $data); }

    function update_bulog($id, $data){
        return $this->db->where('id', $id)->update('bulogs', $data);
    }

    function soft_delete_bulog($id){
        return $this->db->where('id', $id)->update('bulogs', array('deleted_at' => date('Y-m-d H:i:s')));
    }

    /* ======================= ORDER (TRUK) ======================= */

    function get_orders($bulog_id){
        // Urut sesuai kedatangan: waktu timbang pertama; truk yang belum ditimbang di paling bawah
        return $this->db->query("select o.*,
                (select count(*) from bulog_order_weights w where w.bulog_order_id = o.id) as jumlah_timbang,
                (select min(w.created_at) from bulog_order_weights w where w.bulog_order_id = o.id) as pertama_timbang
            from bulog_orders o where o.bulog_id = ?
            order by (pertama_timbang is null) asc, pertama_timbang asc, o.truck_number asc", array($bulog_id))->result();
    }

    function get_order($id){
        return $this->db->query("select o.*,
                (select count(*) from bulog_order_weights w where w.bulog_order_id = o.id) as jumlah_timbang
            from bulog_orders o
            inner join bulogs b on b.id = o.bulog_id and b.deleted_at is null
            where o.id = ?", array($id))->row();
    }

    function insert_order($data){ return $this->db->insert('bulog_orders', $data); }

    function update_order($id, $data){
        return $this->db->where('id', $id)->update('bulog_orders', $data);
    }

    function delete_order($id){
        return $this->db->where('id', $id)->delete('bulog_orders');
    }

    /* ======================= TIMBANGAN ======================= */

    /** Semua timbangan untuk seluruh truk dalam satu bulog, urut created_at ASC */
    function get_weights_by_bulog($bulog_id){
        return $this->db->query("select w.* from bulog_order_weights w
            inner join bulog_orders o on o.id = w.bulog_order_id
            where o.bulog_id = ? order by w.created_at asc", array($bulog_id))->result();
    }

    function get_weight($id){
        return $this->db->query("select w.*, o.bulog_id from bulog_order_weights w
            inner join bulog_orders o on o.id = w.bulog_order_id
            inner join bulogs b on b.id = o.bulog_id and b.deleted_at is null
            where w.id = ?", array($id))->row();
    }

    /**
     * Tambah timbangan. created_at dijamin lebih besar dari timbangan terakhir truk tsb
     * supaya urutan created_at ASC tetap benar walau input cepat (kolom datetime hanya presisi detik).
     */
    function insert_weight($order_id, $weight){
        $now  = date('Y-m-d H:i:s');
        $last = $this->db->query("select max(created_at) as t from bulog_order_weights where bulog_order_id = ?", array($order_id))->row()->t;
        if ($last && $last >= $now) {
            $now = date('Y-m-d H:i:s', strtotime($last) + 1);
        }
        return $this->db->insert('bulog_order_weights', array(
            'id'             => id_primary(),
            'bulog_order_id' => $order_id,
            'weight'         => (int) $weight,
            'created_at'     => $now,
        ));
    }

    function update_weight($id, $weight){
        return $this->db->where('id', $id)->update('bulog_order_weights', array('weight' => (int) $weight));
    }

    function delete_weight($id){
        return $this->db->where('id', $id)->delete('bulog_order_weights');
    }

    /* ======================= REKALKULASI ======================= */

    /** weight_by_truck = SUM(weight) timbangan truk tsb, lalu total bulog ikut dihitung ulang */
    function recalc_order($order_id){
        $this->db->query("update bulog_orders set weight_by_truck =
            (select coalesce(sum(weight),0) from bulog_order_weights where bulog_order_id = ?)
            where id = ?", array($order_id, $order_id));

        $o = $this->db->query("select bulog_id from bulog_orders where id = ?", array($order_id))->row();
        if ($o) $this->recalc_bulog($o->bulog_id);
    }

    /** total_weight = SUM(weight_by_truck) seluruh truk dalam bulog */
    function recalc_bulog($bulog_id){
        $this->db->query("update bulogs set total_weight =
            (select coalesce(sum(weight_by_truck),0) from bulog_orders where bulog_id = ?),
            updated_at = ?
            where id = ?", array($bulog_id, date('Y-m-d H:i:s'), $bulog_id));
    }
}
