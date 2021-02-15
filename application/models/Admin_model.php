<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
        date_default_timezone_set('Asia/Jakarta');
    }
    public function viewUser()
    {
        $user = $this->db->get_where('admin', ['username' => $_SESSION['username']])->row_array();
        return $user;
    }
    public function getAllUser()
    {
        $user = $this->db->get_where('user')->result_array();
        return $user;
    }
    public function getActiveUser()
    {
        $user = $this->db->get_where('user', ['status' => '1'])->result_array();
        return $user;
    }
    public function getNotActiveUser()
    {
        $user = $this->db->get_where('user', ['status' => '0'])->result_array();
        return $user;
    }
    public function getNotPremiumUser()
    {
        $user = $this->db->get_where('user', ['type_acc' => '1'])->result_array();
        return $user;
    }
    public function getPremiumUser()
    {
        $user = $this->db->get_where('user', ['type_acc' => '2'])->result_array();
        return $user;
    }
    public function jumlahTransaksi($type)
    {
        $data = array(
            'type_ft' => $type,
        );
        $this->db->select_sum('jumlah');
        $data = $this->db->get_where('transaction', $data)->row_array();
        return $data;
    }
    public function getAllTrx()
    {
        $this->db->order_by('date', 'DESC');
        $post = $this->db->get_where('transaction')->result_array();
        return $post;
    }
    public function getUser($id)
    {
        $query = $this->db->get_where('user', ['username' => $id]);
        return $query->row_array();
    }
}
