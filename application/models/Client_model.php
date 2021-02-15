<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Client_model extends CI_Model
{
	public function __construct()
	{
		$this->load->database();
		date_default_timezone_set('Asia/Jakarta');
	}
	public function viewUser()
	{
		return $user = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
	}
	public function getUser($max, $min)
	{
		$user = $this->db->get_where('user', ['status' => '1', 'type_acc' => '1'], $max, $min)->result_array();
		return $user;
	}
	public function getAllUser()
	{
		$user = $this->db->get_where('user', ['status' => '1'])->result_array();
		return $user;
	}
	public function get_transaksi_id($kode = false, $iduser = false)
	{
		$user = $this->viewUser();
		if ($kode) {
			$data = array(
				'id_user' => $user['ID_USER'],
				'type_ft' => $kode,
			);
		}
		if ($iduser) {
			$data = array(
				'id_user' => $user['ID_USER'],
			);
		}
		$this->db->order_by('date', 'DESC');
		$post = $this->db->get_where('transaction', $data);
		return $post;
	}
	public function getAllTrx()
	{
		$user = $this->viewUser();
		$data = array(
			'id_user' => $user['ID_USER'],
		);
		$this->db->order_by('date', 'DESC');
		$post = $this->db->get_where('transaction', $data)->result_array();
		return $post;
	}
	public function jumlahTransaksi($type)
	{
		$user = $this->viewUser();
		$data = array(
			'id_user' => $user['ID_USER'],
			'type_ft' => $type,
		);
		$this->db->select_sum('jumlah');
		$data = $this->db->get_where('transaction', $data)->row_array();
		return $data;
	}
	public function add_transaksi($add)
	{
		return $post = $this->db->insert('transaction', $add);
	}
}
