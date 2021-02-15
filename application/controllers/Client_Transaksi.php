<?php
defined('BASEPATH') or exit('No direct script access allowed');
error_reporting(0);
class Client_Transaksi extends CI_Controller
{
	private $ip = '139.195.87.202';

	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
		error_reporting(0);
	}
	public function getIPAddress()
	{
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}
	public function index()
	{
		redirect('client/index');
	}
	public function get_all_count()
	{
		$data = array(
			$this->client_model->jumlahTransaksi('1'),
			$this->client_model->jumlahTransaksi('2'),
			$this->client_model->jumlahTransaksi('3'),
			$this->client_model->jumlahTransaksi('4'),
			$this->client_model->jumlahTransaksi('5'),
			$this->client_model->jumlahTransaksi('6'),
		);
		echo json_encode($data);
	}

	public function getuser()
	{
		// $num = $this->db->get_where('user', ['status' => '1', 'type_acc' => '1'])->result_array();
		// $randommax = rand(50, 100);
		// $randommin = rand(0, count($num) - $randommax);
		// $user = $this->client_model->getUser($randommax, $randommin);
		// foreach ($num as $user) {
		// 	$data_login = array(
		// 		'username' => $user['USERNAME'],
		// 		'ig_did' => $user['IG_DID'],
		// 		'mid' => $user['MID'],
		// 		'ds_user_id' => $user['DS_USER_ID'],
		// 		'useragent' => $user['USERAGENT'],
		// 		'csrftoken' => $user['CSRFTOKEN'],
		// 		'sessionid' => $user['SESSIONID']
		// 	);
		// 	$cekcookie = $this->client_fungsi->getProfile($data_login);
		// 	if ($cekcookie['status'] == 'challenge') {
		// 		$data = array(
		// 			'status' => '0'
		// 		);
		// 		$this->db->update('user', $data, ['username' => $user['USERNAME']]);
		// 		$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Instagram Checkpoint!</div>');
		// 		return redirect('client/index');
		// 	} elseif ($cekcookie['status'] == 'dead') {
		// 		$this->db->delete('user', ['username' => $user['USERNAME']]);
		// 		$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Akun Instagram Anda telah Mati</div>');
		// 		return redirect('client/index');
		// 	} else {
		// 		$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Error not detection!</div>');
		// 		redirect('client/index');
		// 	}
		// 	echo $users['USERNAME'] . ' ' . $users['STATUS'] . '</br>';
		// }
	}

	public function get_transaksi()
	{
		if ($this->input->post('type_ft')) {
			$kode = $this->input->post('type_ft');
			$data = $this->client_model->get_transaksi_id($kode, false)->result();
		}
		if ($this->input->post('id_user')) {
			$iduser = $this->input->post('id_user');
			$mydata = $this->client_model->get_transaksi_id(false, $iduser)->row_array();
			$data = array(
				'year' => substr($mydata['DELAY'], 0, 4),
				'month' => substr($mydata['DELAY'], 5, 2),
				'day' => substr($mydata['DELAY'], 8, 2),
				'h' => substr($mydata['DELAY'], 11, 3),
				'i' => substr($mydata['DELAY'], 14, 2),
				's' => substr($mydata['DELAY'], 16, 2),
			);
		}
		echo json_encode($data);
	}
	public function add_followers()
	{
		$me = $this->client_model->viewUser();
		$mydata = $this->client_model->get_transaksi_id(false, $me['ID_USER'])->row_array();
		$d = ($me['TYPE_ACC'] == 2 || 3 || 4) ? strtotime("+15 Minutes") : strtotime("+30 Minutes");

		if (date('Y-m-d H:i:s') < $mydata['DELAY']) {
			$data = array(
				'msg' => 'delay',
			);
		} else {
			if (isset($_SESSION['username'])) {
				if (!$this->input->post('link')) {
					$data = array(
						'msg' => 'tidak ada link',
					);
				} else {
					$num = $this->db->get_where('user', ['status' => '1', 'type_acc' => '1'])->result_array();
					$randommax = rand(2, 7);
					$randommin = rand(0, count($num) - $randommax);
					$profil = $this->client_model->viewUser();
					$user = $this->client_model->getUser($randommax, $randommin);
					$data_login = array(
						'ig_did' => $profil['IG_DID'],
						'mid' => $profil['MID'],
						'ds_user_id' => $profil['DS_USER_ID'],
						'useragent' => $profil['USERAGENT'],
						'csrftoken' => $profil['CSRFTOKEN'],
						'sessionid' => $profil['SESSIONID']
					);
					$add = array(
						'id_user' => str_replace("'", "", htmlspecialchars($profil['ID_USER'], ENT_QUOTES)),
						'link' => str_replace("'", "", htmlspecialchars($this->input->post('link'), ENT_QUOTES)),
						'jumlah' => str_replace("'", "", htmlspecialchars(count($user), ENT_QUOTES)),
						'date' => str_replace("'", "", htmlspecialchars(date('Y-m-d H:i:s'), ENT_QUOTES)),
						'delay' => str_replace("'", "", htmlspecialchars(date('Y-m-d H:i:s', $d), ENT_QUOTES)),
						'type_ft' => str_replace("'", "", htmlspecialchars($this->input->post('type_ft'), ENT_QUOTES)),
					);
					$profile = $this->client_fungsi->findProfile($add['link'], $data_login);

					if ($profile['details'] == 'user not found') {
						$data = array(
							'msg' => 'username tidak ditemukan',
						);
					} elseif ($me['DS_USER_ID'] !== $profile['id']) {
						$data = array(
							'msg' => 'username tidak sama',
						);
					} else {
						$data = $this->client_model->add_transaksi($add);
						foreach ($user as $users) {
							$data_login = array(
								'username' => $users['USERNAME'],
								'ig_did' => $users['IG_DID'],
								'mid' => $users['MID'],
								'ds_user_id' => $users['DS_USER_ID'],
								'useragent' => $users['USERAGENT'],
								'csrftoken' => $users['CSRFTOKEN'],
								'sessionid' => $users['SESSIONID']
							);
							try {
								$proses = $this->client_fungsi->follow($profile['id'], $data_login);
								if ($proses == true) {
									$data = array(
										'msg' => 'success',
									);
								}
							} catch (Exception $e) {
								$e->getMessage();
							}
						}
					}
				}
			} else {
				$data = array(
					'msg' => 'authorized required',
				);
			}
		}
		echo json_encode($data);
	}
	public function add_likes()
	{
		$me = $this->client_model->viewUser();
		$mydata = $this->client_model->get_transaksi_id(false, $me['ID_USER'])->row_array();
		$d = ($me['TYPE_ACC'] == 2 || 3 || 4) ? strtotime("+15 Minutes") : strtotime("+30 Minutes");
		if (date('Y-m-d H:i:s') < $mydata['DELAY']) {
			$data = array(
				'msg' => 'delay',
			);
		} else {
			if (isset($_SESSION['username'])) {
				if (!$this->input->post('link') || substr($this->input->post('link'), 0, 28) !== "https://www.instagram.com/p/" && substr($this->input->post('link'), 0, 24) !== "https://instagram.com/p/") {
					$data = array(
						'msg' => 'tidak ada link',
					);
				} else {
					$num = $this->db->get_where('user', ['status' => '1', 'type_acc' => '1'])->result_array();

					$profil = $this->client_model->viewUser();
					$data_login = array(
						'ig_did' => $profil['IG_DID'],
						'mid' => $profil['MID'],
						'ds_user_id' => $profil['DS_USER_ID'],
						'useragent' => $profil['USERAGENT'],
						'csrftoken' => $profil['CSRFTOKEN'],
						'sessionid' => $profil['SESSIONID']
					);
					if ($me['TYPE_ACC'] == 1) {
						$randommax = rand(30, 50);
						$randommin = rand(0, count($num) - $randommax);
						$user = $this->client_model->getUser($randommax, $randommin);
						$add = array(
							'id_user' => str_replace("'", "", htmlspecialchars($profil['ID_USER'], ENT_QUOTES)),
							'link' => str_replace("'", "", htmlspecialchars($this->input->post('link'), ENT_QUOTES)),
							'jumlah' => str_replace("'", "", htmlspecialchars(count($user), ENT_QUOTES)),
							'date' => str_replace("'", "", htmlspecialchars(date('Y-m-d H:i:s'), ENT_QUOTES)),
							'delay' => str_replace("'", "", htmlspecialchars(date('Y-m-d H:i:s', $d), ENT_QUOTES)),
							'type_ft' => str_replace("'", "", htmlspecialchars($this->input->post('type_ft'), ENT_QUOTES)),
						);
						$link = $this->client_fungsi->getArtikel($add['link'], $data_login);
						if ($me['DS_USER_ID'] != $link['user']) {
							$data = array(
								'msg' => 'username tidak sama',
							);
						} else {
							$link = $link['id'];
							$data = $this->client_model->add_transaksi($add);
							foreach ($user as $users) {
								$data_login = array(
									'username' => $users['USERNAME'],
									'ig_did' => $users['IG_DID'],
									'mid' => $users['MID'],
									'ds_user_id' => $users['DS_USER_ID'],
									'useragent' => $users['USERAGENT'],
									'csrftoken' => $users['CSRFTOKEN'],
									'sessionid' => $users['SESSIONID']
								);
								try {
									$proses = $this->client_fungsi->like($link, $data_login);
									if ($proses['status'] == 'success') {
										$data[] = array(
											'msg' => 'success',
										);
									}
								} catch (Exception $e) {
									$e->getMessage();
								}
							}
						}
					} elseif ($me['TYPE_ACC'] == 2 || 3 || 4) {
						$randommax = rand(50, 100);
						$randommin = rand(0, count($num) - $randommax);
						$user = $this->client_model->getUser($randommax, $randommin);
						$add = array(
							'id_user' => str_replace("'", "", htmlspecialchars($profil['ID_USER'], ENT_QUOTES)),
							'link' => str_replace("'", "", htmlspecialchars($this->input->post('link'), ENT_QUOTES)),
							'jumlah' => str_replace("'", "", htmlspecialchars(count($user), ENT_QUOTES)),
							'date' => str_replace("'", "", htmlspecialchars(date('Y-m-d H:i:s'), ENT_QUOTES)),
							'delay' => str_replace("'", "", htmlspecialchars(date('Y-m-d H:i:s', $d), ENT_QUOTES)),
							'type_ft' => str_replace("'", "", htmlspecialchars($this->input->post('type_ft'), ENT_QUOTES)),
						);
						$link = $this->client_fungsi->getArtikel($add['link'], $data_login);
						$link = $link['id'];
						$data = $this->client_model->add_transaksi($add);
						foreach ($user as $users) {
							$data_login = array(
								'username' => $users['USERNAME'],
								'ig_did' => $users['IG_DID'],
								'mid' => $users['MID'],
								'ds_user_id' => $users['DS_USER_ID'],
								'useragent' => $users['USERAGENT'],
								'csrftoken' => $users['CSRFTOKEN'],
								'sessionid' => $users['SESSIONID']
							);
							try {
								$proses = $this->client_fungsi->like($link, $data_login);
								if ($proses == true) {
									$data = array(
										'msg' => 'success',
									);
								}
							} catch (Exception $e) {
								$e->getMessage();
							}
						}
					}
				}
			} else {
				$data = array(
					'msg' => 'authorized required',
				);
			}
		}
		echo json_encode($data);
	}

	public function cek_comments()
	{
		$me = $this->client_model->viewUser();
		$mydata = $this->client_model->get_transaksi_id(false, $me['ID_USER'])->row_array();
		if (date('Y-m-d H:i:s') < $mydata['DELAY']) {
			$data = array(
				'msg' => 'delay',
			);
		} else {
			if (isset($_SESSION['username'])) {
				if (!$this->input->post('link') || substr($this->input->post('link'), 0, 28) !== "https://www.instagram.com/p/" && substr($this->input->post('link'), 0, 24) !== "https://instagram.com/p/") {
					$data = array(
						'msg' => 'tidak ada link',
					);
				} else {
					$profil = $this->client_model->viewUser();
					$data_login = array(
						'ig_did' => $profil['IG_DID'],
						'mid' => $profil['MID'],
						'ds_user_id' => $profil['DS_USER_ID'],
						'useragent' => $profil['USERAGENT'],
						'csrftoken' => $profil['CSRFTOKEN'],
						'sessionid' => $profil['SESSIONID']
					);
					$add = array(
						'id_user' => str_replace("'", "", htmlspecialchars($profil['ID_USER'], ENT_QUOTES)),
						'link' => str_replace("'", "", htmlspecialchars($this->input->post('link'), ENT_QUOTES)),
						'date' => str_replace("'", "", htmlspecialchars(date('Y-m-d H:i:s'), ENT_QUOTES)),
					);
					try {
						$artikel = $this->client_fungsi->getComment($add['link'], $data_login);
						if ($artikel['username'] != $_SESSION['username']) {
							$data = array(
								'msg' => 'username tidak ditemukan'
							);
						} elseif ($artikel['username'] == $_SESSION['username']) {
							$data = array(
								'msg' => 'username ditemukan',
								'status' => 'success',
								'id' => $artikel['id'],
								'commentid' => $artikel['commentid'],
								'username' => $artikel['username']
							);
						}
					} catch (Exception $e) {
						$e->getMessage();
					}
				}
			} else {
				$data = array(
					'msg' => 'authorized required',
				);
			}
		}
		echo json_encode($data);
	}

	public function add_comments_likes()
	{
		$me = $this->client_model->viewUser();
		$mydata = $this->client_model->get_transaksi_id(false, $me['ID_USER'])->row_array();
		$d = ($me['TYPE_ACC'] == 2 || 3 || 4) ? strtotime("+15 Minutes") : strtotime("+30 Minutes");
		if (date('Y-m-d H:i:s') < $mydata['DELAY']) {
			$data = array(
				'msg' => 'delay',
			);
		} else {
			if (isset($_SESSION['username'])) {
				if (!$this->input->post('link') || substr($this->input->post('link'), 0, 28) !== "https://www.instagram.com/p/" && substr($this->input->post('link'), 0, 24) !== "https://instagram.com/p/") {
					$data = array(
						'msg' => 'tidak ada link',
					);
				} else {
					$num = $this->db->get_where('user', ['status' => '1', 'type_acc' => '1'])->result_array();
					$profil = $this->client_model->viewUser();
					$data_login = array(
						'ig_did' => $profil['IG_DID'],
						'mid' => $profil['MID'],
						'ds_user_id' => $profil['DS_USER_ID'],
						'useragent' => $profil['USERAGENT'],
						'csrftoken' => $profil['CSRFTOKEN'],
						'sessionid' => $profil['SESSIONID']
					);
					if ($me['TYPE_ACC'] == 1) {
						$randommax = rand(30, 50);
						$randommin = rand(0, count($num) - $randommax);
						$user = $this->client_model->getUser($randommax, $randommin);
						$add = array(
							'id_user' => str_replace("'", "", htmlspecialchars($profil['ID_USER'], ENT_QUOTES)),
							'link' => str_replace("'", "", htmlspecialchars($this->input->post('link'), ENT_QUOTES)),
							'jumlah' => str_replace("'", "", htmlspecialchars(count($user), ENT_QUOTES)),
							'date' => str_replace("'", "", htmlspecialchars(date('Y-m-d H:i:s'), ENT_QUOTES)),
							'delay' => str_replace("'", "", htmlspecialchars(date('Y-m-d H:i:s', $d), ENT_QUOTES)),
							'type_ft' => str_replace("'", "", htmlspecialchars($this->input->post('type_ft'), ENT_QUOTES)),
						);
					} elseif ($me['TYPE_ACC'] == 2) {
						$randommax = rand(50, 100);
						$randommin = rand(0, count($num) - $randommax);
						$user = $this->client_model->getUser($randommax, $randommin);
						$add = array(
							'id_user' => str_replace("'", "", htmlspecialchars($profil['ID_USER'], ENT_QUOTES)),
							'link' => str_replace("'", "", htmlspecialchars($this->input->post('link'), ENT_QUOTES)),
							'jumlah' => str_replace("'", "", htmlspecialchars(count($user), ENT_QUOTES)),
							'date' => str_replace("'", "", htmlspecialchars(date('Y-m-d H:i:s'), ENT_QUOTES)),
							'delay' => str_replace("'", "", htmlspecialchars(date('Y-m-d H:i:s', $d), ENT_QUOTES)),
							'type_ft' => str_replace("'", "", htmlspecialchars($this->input->post('type_ft'), ENT_QUOTES)),
						);
					}
					$data = $this->client_model->add_transaksi($add);
					foreach ($user as $users) {
						$data_login = array(
							'username' => $users['USERNAME'],
							'ig_did' => $users['IG_DID'],
							'mid' => $users['MID'],
							'ds_user_id' => $users['DS_USER_ID'],
							'useragent' => $users['USERAGENT'],
							'csrftoken' => $users['CSRFTOKEN'],
							'sessionid' => $users['SESSIONID']
						);
						try {
							$proses = $this->client_fungsi->commentlike($add['link'], $data_login);
							if ($proses == true) {
								$data = array(
									'msg' => 'success',
								);
							}
						} catch (Exception $e) {
							$e->getMessage();
						}
					}
				}
			} else {
				$data = array(
					'msg' => 'authorized required',
				);
			}
		}
		echo json_encode($data);
	}

	public function add_comments()
	{
		$me = $this->client_model->viewUser();
		$mydata = $this->client_model->get_transaksi_id(false, $me['ID_USER'])->row_array();
		$d = ($me['TYPE_ACC'] == 2 || 3 || 4) ? strtotime("+15 Minutes") : strtotime("+30 Minutes");
		if (date('Y-m-d H:i:s') < $mydata['DELAY']) {
			$data = array(
				'msg' => 'delay',
			);
		} else {
			if (isset($_SESSION['username'])) {
				if (!$this->input->post('link') || substr($this->input->post('link'), 0, 28) !== "https://www.instagram.com/p/" && substr($this->input->post('link'), 0, 24) !== "https://instagram.com/p/") {
					$data = array(
						'msg' => 'tidak ada link',
					);
				} elseif (!$this->input->post('text')) {
					$data = array(
						'msg' => 'tidak ada komen',
					);
				} else {
					$num = $this->db->get_where('user', ['status' => '1', 'type_acc' => '1'])->result_array();
					$randommax = ($me['TYPE_ACC'] == 2 || 3 || 4) ? rand(3, 7) : rand(1, 3);
					$randommin = rand(0, count($num) - $randommax);
					$profil = $this->client_model->viewUser();
					$data_login = array(
						'ig_did' => $profil['IG_DID'],
						'mid' => $profil['MID'],
						'ds_user_id' => $profil['DS_USER_ID'],
						'useragent' => $profil['USERAGENT'],
						'csrftoken' => $profil['CSRFTOKEN'],
						'sessionid' => $profil['SESSIONID']
					);
					$user = $this->client_model->getUser($randommax, $randommin);
					$comment = explode("\n", $this->input->post('text'));
					$comment = ($me['TYPE_ACC'] == 2 || 3 || 4) ? $comment : $this->input->post('text');
					$add = array(
						'id_user' => str_replace("'", "", htmlspecialchars($profil['ID_USER'], ENT_QUOTES)),
						'link' => str_replace("'", "", htmlspecialchars($this->input->post('link'), ENT_QUOTES)),
						'jumlah' => str_replace("'", "", htmlspecialchars(count($user), ENT_QUOTES)),
						'date' => str_replace("'", "", htmlspecialchars(date('Y-m-d H:i:s'), ENT_QUOTES)),
						'delay' => str_replace("'", "", htmlspecialchars(date('Y-m-d H:i:s', $d), ENT_QUOTES)),
						'type_ft' => str_replace("'", "", htmlspecialchars($this->input->post('type_ft'), ENT_QUOTES)),
					);
					$link = $this->client_fungsi->getArtikel($add['link'], $data_login);
					if ($me['TYPE_ACC'] == 1) {
						if ($me['DS_USER_ID'] != $link['user']) {
							$data = array(
								'msg' => 'username tidak sama',
							);
						} else {
							$data = $this->client_model->add_transaksi($add);
							foreach ($user as $users) {
								$data_login = array(
									'username' => $users['USERNAME'],
									'ig_did' => $users['IG_DID'],
									'mid' => $users['MID'],
									'ds_user_id' => $users['DS_USER_ID'],
									'useragent' => $users['USERAGENT'],
									'csrftoken' => $users['CSRFTOKEN'],
									'sessionid' => $users['SESSIONID']
								);
								$devkey = $comment[rand(0, (count($comment) - 1))];
								try {
									$proses = $this->client_fungsi->comment($link['id'], $data_login, $devkey);
									if ($proses == true) {
										$data = array(
											'msg' => 'success',
										);
									}
								} catch (Exception $e) {
									$e->getMessage();
								}
							}
						}
					} elseif ($me['TYPE_ACC'] == 2 || 3 || 4) {
						$data = $this->client_model->add_transaksi($add);
						foreach ($user as $users) {
							$data_login = array(
								'username' => $users['USERNAME'],
								'ig_did' => $users['IG_DID'],
								'mid' => $users['MID'],
								'ds_user_id' => $users['DS_USER_ID'],
								'useragent' => $users['USERAGENT'],
								'csrftoken' => $users['CSRFTOKEN'],
								'sessionid' => $users['SESSIONID']
							);
							$devkey = $comment[rand(0, (count($comment) - 1))];
							try {
								$proses = $this->client_fungsi->comment($link['id'], $data_login, $devkey);
								if ($proses == true) {
									$data = array(
										'msg' => 'success',
									);
								}
							} catch (Exception $e) {
								$e->getMessage();
							}
						}
					}
				}
			} else {
				$data = array(
					'msg' => 'authorized required',
				);
			}
		}
		echo json_encode($data);
	}
}
