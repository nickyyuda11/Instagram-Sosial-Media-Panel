<?php
defined('BASEPATH') or exit('No direct script access allowed');
error_reporting(0);
class Client extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
		$this->load->database();
	}
	public function index()
	{
		if (!isset($_SESSION['username'])) {
			$data['title'] = 'Instagram Tools Auto Like, Followers, Comments Real - Semanggipanel';
			$this->load->view('client/template/header', $data);
			$this->load->view('client/body');
			$this->load->view('client/template/homefooter');
		} else {
			$user = $this->client_model->viewUser();
			$header = array(
				'ig_did' => $user['IG_DID'],
				'mid' => $user['MID'],
				'ds_user_id' => $user['DS_USER_ID'],
				'useragent' => $user['USERAGENT'],
				'csrftoken' => $user['CSRFTOKEN'],
				'sessionid' => $user['SESSIONID']
			);
			$data['title'] = 'Dashboard Instagram Tools Auto Like, Followers, Comments Real - Semanggipanel';
			$data['profile'] = $this->client_fungsi->findProfile($user['USERNAME'], $header);
			$data['trx'] = $this->client_model->getAllTrx();
			$data['user'] = $user;
			$this->load->view('client/template/header', $data);
			$this->load->view('client/panel', $data);
			$this->load->view('client/modal', $data);
			$this->load->view('client/template/footer');
		}
	}
	public function login()
	{
		$username = $this->input->post('username');
		$password = $this->input->post('password');
		## URL ##
		$url_login = 'https://www.instagram.com/accounts/login/ajax/';
		$url_ig = 'https://www.instagram.com/accounts/login/';

		## LOGIN ##
		$user = $this->db->get_where('user', ['username' => $username])->row_array();
		if ($user) {
			$data_login = array(
				'username' => $user['USERNAME'],
				'ig_did' => $user['IG_DID'],
				'mid' => $user['MID'],
				'ds_user_id' => $user['DS_USER_ID'],
				'useragent' => $user['USERAGENT'],
				'csrftoken' => $user['CSRFTOKEN'],
				'sessionid' => $user['SESSIONID']
			);
			$cekcookie = $this->client_fungsi->getProfile($data_login);
			var_dump($cekcookie);

			if ($cekcookie['status'] == 'error') {
				$get = $this->client_fungsi->curl($url_ig);
				$cookie = $this->client_fungsi->fetchCurlCookies($get);
				$ig_did = $cookie['ig_did'];
				$csrf = $cookie['csrftoken'];
				$mid = $cookie['mid'];
				$useragent = $this->client_fungsi->generate_useragent();

				## HEADER LOGIN ##
				$header = array(
					'origin: https://www.instagram.com',
					'referer: https://www.instagram.com/',
					'User-Agent: ' . $useragent,
					'X-CSRFToken: ' . $csrf,
					'Content-Type: application/x-www-form-urlencoded',
					'Cookie: ig_did=' . $ig_did . '; rur=FTW; mid=' . $mid . '; csrftoken=' . $csrf . '',
				);
				## BODY LOGIN ##    
				$body = 'username=' . $username . '&enc_password=#PWD_INSTAGRAM_BROWSER:0:' . time() . ':' . $password . '&queryParams=%7B%7D&optIntoOneTap=false';
				$post = $this->client_fungsi->curl($url_login, $body, $header);

				if (strpos($post, '"authenticated":false')) {
					$data = array(
						'action' => 'login',
						'status' => 'error',
						'username' => $username,
					);
					$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Wrong password!</div>');
					redirect('client/index');
				} elseif (strpos($post, '"authenticated":true')) {
					$cookie_log = $this->client_fungsi->fetchCurlCookies($post);
					$data = array(
						'username' => $username,
						'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
						'ig_did' => $ig_did,
						'mid' => $mid,
						'ds_user_id' => $cookie_log['ds_user_id'],
						'csrftoken' => $cookie_log['csrftoken'],
						'sessionid' => $cookie_log['sessionid'],
						'useragent' => $useragent,
						'created' => date('Y-m-d h:i:s'),
						'type_acc' => '1',
						'status' => '1'
					);
					$this->db->update('user', $data, ['username' => $user['USERNAME']]);
					$this->session->set_userdata($data);
					return redirect('client/index');
				} elseif (strpos($post, '"checkpoint_required"')) {
					$data = array(
						'status' => '0'
					);
					$this->db->update('user', $data, ['username' => $user['USERNAME']]);
					$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Instagram Checkpoint!</div>');
					return redirect('client/index');
				} else {
					$data = $post;
					return redirect('client/index');
				}
			} elseif ($cekcookie['status'] == 'challenge') {
				$data = array(
					'status' => '0'
				);
				$this->db->update('user', $data, ['username' => $user['USERNAME']]);
				$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Instagram Checkpoint!</div>');
				return redirect('client/index');
			} elseif ($cekcookie['status'] == 'dead') {
				$this->db->delete('user', ['username' => $user['USERNAME']]);
				$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Akun Instagram Anda telah Mati</div>');
				return redirect('client/index');
			} elseif ($cekcookie['status'] == 'success') {
				if (password_verify($password, $user['PASSWORD'])) {
					$data = [
						'username' => $user['USERNAME'],
						'id_user' => $user['ID_USER']
					];
					$this->session->set_userdata($data);
					redirect('client/index');
				} else {
					$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Wrong password!</div>');
					redirect('client/index');
				}
			} else {
				$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Error not detection!</div>');
				redirect('client/index');
			}
		} else {
			$get = $this->client_fungsi->curl($url_ig);
			$cookie = $this->client_fungsi->fetchCurlCookies($get);
			$ig_did = $cookie['ig_did'];
			$csrf = $cookie['csrftoken'];
			$mid = $cookie['mid'];
			$useragent = $this->client_fungsi->generate_useragent();

			## HEADER LOGIN ##
			$header = array(
				'origin: https://www.instagram.com',
				'referer: https://www.instagram.com/',
				'User-Agent: ' . $useragent,
				'X-CSRFToken: ' . $csrf,
				'Content-Type: application/x-www-form-urlencoded',
				'Cookie: ig_did=' . $ig_did . '; rur=FTW; mid=' . $mid . '; csrftoken=' . $csrf . '',
			);
			## BODY LOGIN ##    
			$body = 'username=' . $username . '&enc_password=#PWD_INSTAGRAM_BROWSER:0:' . time() . ':' . $password . '&queryParams=%7B%7D&optIntoOneTap=false';
			$post = $this->client_fungsi->curl($url_login, $body, $header);

			if (strpos($post, '"authenticated":false')) {
				$data = array(
					'action' => 'login',
					'status' => 'error',
					'username' => $username,
				);
				return redirect('client/index');
			} elseif (strpos($post, '"checkpoint_required"')) {
				$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Instagram Checkpoint!</div>');
				return redirect('client/index');
			} elseif (strpos($post, 'viewer":null')) {
				$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Akun Instagram Anda tidak ditemukan</div>');
				return redirect('client/index');
			} elseif (strpos($post, '"authenticated":true')) {
				$cookie_log = $this->client_fungsi->fetchCurlCookies($post);
				$data = array(
					'username' => $username,
					'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
					'ds_user_id' => $cookie_log['ds_user_id'],
					'csrftoken' => $cookie_log['csrftoken'],
					'sessionid' => $cookie_log['sessionid'],
					'ig_did' => $ig_did,
					'mid' => $mid,
					'useragent' => $useragent,
					'created' => date('Y-m-d h:i:s'),
					'type_acc' => '1',
					'status' => '1'
				);
				$this->db->insert('user', $data);
				$this->session->set_userdata($data);
				return redirect('client/index');
			} else {
				$data = $post;
				return redirect('client/index');
			}
		}
		return $data;
	}
	public function logout()
	{
		$this->session->unset_userdata('username');
		$this->session->unset_userdata('id_user');
		redirect('client/index');
	}
	public function about()
	{
		$data['title'] = 'Instagram Tools Auto Like, Followers, Comments Real - Semanggipanel';
		$this->load->view('client/template/header', $data);
		$this->load->view('client/about');
		$this->load->view('client/template/homefooter');
	}
	public function services()
	{
		$data['title'] = 'Instagram Tools Auto Like, Followers, Comments Real - Semanggipanel';
		$this->load->view('client/template/header', $data);
		$this->load->view('client/service');
		$this->load->view('client/template/homefooter');
	}
	public function pricing()
	{
		$data['title'] = 'Instagram Tools Auto Like, Followers, Comments Real - Semanggipanel';
		$this->load->view('client/template/header', $data);
		$this->load->view('client/pricing');
		$this->load->view('client/template/homefooter');
	}
	public function blog()
	{
		$data['title'] = 'Instagram Tools Auto Like, Followers, Comments Real - Semanggipanel';
		$this->load->view('client/template/header', $data);
		$this->load->view('client/blog');
		$this->load->view('client/template/homefooter');
	}
	public function blog_single()
	{
		$data['title'] = 'Instagram Tools Auto Like, Followers, Comments Real - Semanggipanel';
		$this->load->view('client/template/header', $data);
		$this->load->view('client/blog_single');
		$this->load->view('client/template/homefooter');
	}
	public function contact()
	{
		$data['title'] = 'Instagram Tools Auto Like, Followers, Comments Real - Semanggipanel';
		$this->load->view('client/template/header', $data);
		$this->load->view('client/contact');
		$this->load->view('client/template/homefooter');
	}
}
