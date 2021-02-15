<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Client_Cek extends CI_Controller
{
	public function index()
	{
		$url_login = 'https://www.instagram.com/accounts/login/ajax/';
		$url_ig = 'https://www.instagram.com/accounts/login/';
		$get = $this->client_fungsi->curl($url_ig);
		$cookie = $this->client_fungsi->fetchCurlCookies($get);
		$ig_did = $cookie['ig_did'];
		$csrf = $cookie['csrftoken'];
		$mid = $cookie['mid'];
		$useragent = $this->client_fungsi->getUserAgent();

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
		## LOGIN ##
		$user = $this->client_model->getAllUser();

		foreach ($user as $users) {
			$data_login = array(
				'username' => $users['USERNAME'],
				'password' => $users['PASSWORD'],
				'ig_did' => $users['IG_DID'],
				'mid' => $users['MID'],
				'ds_user_id' => $users['DS_USER_ID'],
				'useragent' => $users['USERAGENT'],
				'csrftoken' => $users['CSRFTOKEN'],
				'sessionid' => $users['SESSIONID']
			);
			$body = 'username=' . $data_login['username'] . '&enc_password=#PWD_INSTAGRAM_BROWSER:0:' . time() . ':' . password_hash($data_login['password'], PASSWORD_DEFAULT) . '&queryParams=%7B%7D&optIntoOneTap=false';
			$cekcookie = $this->client_fungsi->activity($data_login);
			var_dump($cekcookie);
			die;

			if ($cekcookie['status'] == 'error') {
				$post = $this->client_fungsi->curl($url_login, $body, $header);
				if (strpos($post, '"authenticated":false')) {
					$data = array(
						'action' => 'login',
						'status' => 'error',
						'username' => $data_login['username'],
					);
				} elseif (strpos($post, '"authenticated":true')) {
					$cookie_log = $this->client_fungsi->fetchCurlCookies($post);
					$data = array(
						'username' => $data_login['username'],
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
					$this->db->update('user', $data, ['username' => $data_login['username']]);
					$this->session->set_userdata($data);
				} elseif (strpos($post, '{"message": "checkpoint_required"')) {
					$data = array(
						'status' => '0'
					);
					$this->db->update('user', $data, ['username' => $data_login['username']]);
				} else {
					$data = $post;
				}
			}
		}
	}
}
