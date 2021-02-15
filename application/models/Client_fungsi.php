<?php
defined('BASEPATH') or exit('No direct script access allowed');
error_reporting(0);
class Client_fungsi extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
	}
	private function GenerateSignature($data)
	{
		$instaSignature = '25eace5393646842f0d0c3fb2ac7d3cfa15c052436ee86b5406a8433f54d24a5';
		return hash_hmac('sha256', $data, $instaSignature);
	}
	private function GenerateGuid()
	{
		return sprintf(
			'%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
			mt_rand(0, 65535),
			mt_rand(0, 65535),
			mt_rand(0, 65535),
			mt_rand(16384, 20479),
			mt_rand(32768, 49151),
			mt_rand(0, 65535),
			mt_rand(0, 65535),
			mt_rand(0, 65535)
		);
	}
	public function findProfile($username, $session = 0)
	{
		$url = 'https://instagram.com/' . $username;
		if ($session != 0) {
			$header = array(
				'origin: https://www.instagram.com',
				'referer: https://www.instagram.com/',
				'User-Agent:' . $session['useragent'],
				'X-CSRFToken: ' . $session['csrftoken'],
				'Cookie: ig_did=' . $session['ig_did'] . '; mid=' . $session['mid'] . '; csrftoken=' . $session['csrftoken'] . '; ds_user_id=' . $session['ds_user_id'] . '; sessionid=' . $session['sessionid']
			);
			$get = $this->curl($url, 0, $header);
		} else {
			$get = $this->curl($url);
		}
		if (strpos($get, 'The link you followed may be broken, or the page may have been removed.')) {
			$data = array(
				'status' => 'error',
				'details' => 'user not found'
			);
		} else {
			$data_ouput = $this->getStr($get, '<script type="text/javascript">window._sharedData = ', ';</script>');
			$data_array = json_decode($data_ouput);
			$result = $data_array->entry_data->ProfilePage['0']->graphql->user;
			if (empty($result->edge_owner_to_timeline_media->edges) && $result->edge_owner_to_timeline_media->count >= 1) {
				$data = array(
					'status' => 'error',
					'details' => 'account public'
				);
			} else {
				$result = $data_array->entry_data->ProfilePage['0']->graphql->user;
				// $vid = ($result->is_video == 1) ? "yes" : "no" ;
				$is_follow = ($result->followed_by_viewer) ? 'true' : 'false';
				$is_verified = ($result->is_verified) ? 'true' : 'false';
				$is_polbek = ($result->follows_viewer) ? 'true' : 'false';

				$data = array(
					'status' => 'success',
					'username' => $username,
					'foto' => $result->profile_pic_url_hd,
					'fullname' => $result->full_name,
					'bio' => $result->biography,
					'followers' => $result->edge_followed_by->count,
					'following' => $result->edge_follow->count,
					'is_follow' => $is_follow,
					'is_polbek' => $is_polbek,
					'id' => $result->id,
					'is_verif' => $is_verified,
					'post' => $result->edge_owner_to_timeline_media->count,
					'tespost' => $result->edge_owner_to_timeline_media,
				);
			}
		}

		return $data;
	}
	public function activity($session)
	{
		$url = 'https://www.instagram.com/accounts/activity/';
		$header = array(
			'origin: https://www.instagram.com',
			'referer: https://www.instagram.com/',
			// 'User-Agent: ' . $session['useragent'],
			'User-Agent: Mozilla/5.0 (iPhone; CPU iPhone OS 11_0_1 like Mac OS X) AppleWebKit/604.1.38 (KHTML, like Gecko) Version/11.0 Mobile/15A402 Safari/604.1',
			'X-CSRFToken: ' . $session['csrftoken'],
			'Cookie: ig_did=' . $session['ig_did'] . '; mid=' . $session['mid'] . '; csrftoken=' . $session['csrftoken'] . '; ds_user_id=' . $session['ds_user_id'] . '; sessionid=' . $session['sessionid']
		);
		$post = $this->curl($url, 1, $header);
		$data_ouput = $this->getStr($post, '<script type="text/javascript">window._sharedData = ', ';</script>');
		$data_array = json_decode($data_ouput);
		$result = $data_array->config;
		$username = $data_array->config->viewer->username;

		if (strpos($post, 'ReviewLoginForm')) {
			$data = array(
				'status' => 'challenge',
			);
		} elseif (strpos($post, 'viewer":null')) {
			$data = array(
				'status' => 'dead',
			);
		} elseif ($username == $session['username']) {
			$data = array(
				'status' => 'success',
				'csrftoken' => $result->csrf_token,
				'username' => $username
			);
		} else {
			$data = array(
				'status' => 'error',
			);
		}
		return $data;
	}
	public function curl($url, $data = 0, $header = 0, $cookie = 0)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		// curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		if ($header) {
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
			curl_setopt($ch, CURLOPT_ENCODING, "gzip");
		}
		if ($data) {
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		}
		if ($cookie) {
			curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
			curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
		}
		$x = curl_exec($ch);
		curl_close($ch);
		return $x;
	}
	public function curlNoHeader($url, $data = 0, $header = 0, $cookie = 0)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

		curl_setopt($ch, CURLOPT_HEADER, 0);
		if ($header) {
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
			curl_setopt($ch, CURLOPT_ENCODING, "gzip");
		}
		if ($data) {
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		}
		if ($cookie) {
			curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
			curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
		}
		$x = curl_exec($ch);
		curl_close($ch);
		return $x;
	}
	public function fetchCurlCookies($source)
	{
		preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $source, $matches);
		$cookies = array();
		foreach ($matches[1] as $item) {
			parse_str($item, $cookie);
			$cookies = array_merge($cookies, $cookie);
		}
		return $cookies;
	}
	public function getUserAgent()
	{
		$userAgentArray = array(
			"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36",
			"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.84 Safari/537.36",
			"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:57.0) Gecko/20100101 Firefox/57.0",
			"Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36",
			"Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.84 Safari/537.36",
			"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36",
			"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36",
			"Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:57.0) Gecko/20100101 Firefox/57.0",
			"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_1) AppleWebKit/604.3.5 (KHTML, like Gecko) Version/11.0.1 Safari/604.3.5",
			"Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:57.0) Gecko/20100101 Firefox/57.0",
			"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.84 Safari/537.36",
			"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.89 Safari/537.36 OPR/49.0.2725.47",
			"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_2) AppleWebKit/604.4.7 (KHTML, like Gecko) Version/11.0.2 Safari/604.4.7",
			"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.84 Safari/537.36",
			"Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36",
			"Mozilla/5.0 (Macintosh; Intel Mac OS X 10.13; rv:57.0) Gecko/20100101 Firefox/57.0",
			"Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko",
			"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36",
			"Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36",
			"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.108 Safari/537.36",
			"Mozilla/5.0 (X11; Linux x86_64; rv:57.0) Gecko/20100101 Firefox/57.0",
			"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.116 Safari/537.36 Edge/15.15063",
			"Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36",
			"Mozilla/5.0 (Macintosh; Intel Mac OS X 10.12; rv:57.0) Gecko/20100101 Firefox/57.0",
			"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36 Edge/16.16299",
			"Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.84 Safari/537.36",
			"Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.84 Safari/537.36",
			"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.84 Safari/537.36",
			"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_6) AppleWebKit/604.4.7 (KHTML, like Gecko) Version/11.0.2 Safari/604.4.7",
			"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_6) AppleWebKit/604.3.5 (KHTML, like Gecko) Version/11.0.1 Safari/604.3.5",
			"Mozilla/5.0 (X11; Linux x86_64; rv:52.0) Gecko/20100101 Firefox/52.0",
			"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36",
			"Mozilla/5.0 (Windows NT 6.3; Win64; x64; rv:57.0) Gecko/20100101 Firefox/57.0",
			"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.84 Safari/537.36",
			"Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.84 Safari/537.36",
			"Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.108 Safari/537.36",
			"Mozilla/5.0 (Windows NT 10.0; WOW64; Trident/7.0; rv:11.0) like Gecko",
			"Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:52.0) Gecko/20100101 Firefox/52.0",
			"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36 OPR/49.0.2725.64",
			"Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.108 Safari/537.36",
			"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36",
			"Mozilla/5.0 (Windows NT 6.1; rv:57.0) Gecko/20100101 Firefox/57.0",
			"Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.106 Safari/537.36",
			"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36",
			"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/604.4.7 (KHTML, like Gecko) Version/11.0.2 Safari/604.4.7",
			"Mozilla/5.0 (Macintosh; Intel Mac OS X 10.11; rv:57.0) Gecko/20100101 Firefox/57.0",
			"Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/62.0.3202.94 Chrome/62.0.3202.94 Safari/537.36",
			"Mozilla/5.0 (Windows NT 10.0; WOW64; rv:56.0) Gecko/20100101 Firefox/56.0",
			"Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36",
			"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:58.0) Gecko/20100101 Firefox/58.0",
			"Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36",
			"Mozilla/5.0 (Windows NT 6.1; Trident/7.0; rv:11.0) like Gecko",
			"Mozilla/5.0 (Windows NT 6.1; WOW64; rv:52.0) Gecko/20100101 Firefox/52.0",
			"Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Trident/5.0;  Trident/5.0)",
			"Mozilla/5.0 (Windows NT 6.1; rv:52.0) Gecko/20100101 Firefox/52.0",
			"Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/63.0.3239.84 Chrome/63.0.3239.84 Safari/537.36",
			"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36",
			"Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36",
			"Mozilla/5.0 (X11; Fedora; Linux x86_64; rv:57.0) Gecko/20100101 Firefox/57.0",
			"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:56.0) Gecko/20100101 Firefox/56.0",
			"Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36",
			"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.108 Safari/537.36",
			"Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.89 Safari/537.36",
			"Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.0; Trident/5.0;  Trident/5.0)",
			"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_5) AppleWebKit/603.3.8 (KHTML, like Gecko) Version/10.1.2 Safari/603.3.8",
			"Mozilla/5.0 (Windows NT 6.1; WOW64; rv:57.0) Gecko/20100101 Firefox/57.0",
			"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36",
			"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/604.3.5 (KHTML, like Gecko) Version/11.0.1 Safari/604.3.5",
			"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_6) AppleWebKit/603.3.8 (KHTML, like Gecko) Version/10.1.2 Safari/603.3.8",
			"Mozilla/5.0 (Windows NT 10.0; WOW64; rv:57.0) Gecko/20100101 Firefox/57.0",
			"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.79 Safari/537.36 Edge/14.14393",
			"Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:56.0) Gecko/20100101 Firefox/56.0",
			"Mozilla/5.0 (iPad; CPU OS 11_1_2 like Mac OS X) AppleWebKit/604.3.5 (KHTML, like Gecko) Version/11.0 Mobile/15B202 Safari/604.1",
			"Mozilla/5.0 (Windows NT 10.0; WOW64; Trident/7.0; Touch; rv:11.0) like Gecko",
			"Mozilla/5.0 (Macintosh; Intel Mac OS X 10.13; rv:58.0) Gecko/20100101 Firefox/58.0",
			"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13) AppleWebKit/604.1.38 (KHTML, like Gecko) Version/11.0 Safari/604.1.38",
			"Mozilla/5.0 (Windows NT 10.0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36",
			"Mozilla/5.0 (X11; CrOS x86_64 9901.77.0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.97 Safari/537.36"
		);

		return $devkey = $userAgentArray[rand(0, (count($userAgentArray) - 1))];
	}
	public function generate_useragent($sign_version = '138.0.0.26.117')
	{
		$resolusi = array(
			'1080x1776',
			'1080x1920',
			'720x1280',
			'320x480',
			'480x800',
			'1024x768',
			'1280x720',
			'768x1024',
			'480x320'
		);
		$versi    = array(
			'GT-N7000',
			'SM-N9000',
			'GT-I9220',
			'GT-I9100'
		);
		$dpi      = array(
			'120',
			'160',
			'320',
			'240'
		);
		$ver = $versi[array_rand($versi)];
		$data = 'Instagram ' . $sign_version . ' Android (' . mt_rand(10, 11) . '/' . mt_rand(1, 3) . '.' . mt_rand(3, 5) . '.' . mt_rand(0, 5) . '; ' . $dpi[array_rand($dpi)] . '; ' . $resolusi[array_rand($resolusi)] . '; samsung; ' . $ver . '; ' . $ver . '; smdkc210; en_US)';
		return $data;
	}
	public function getStr($string, $start, $end)
	{
		$str = explode($start, $string);
		$str = explode($end, $str[1]);
		return $str[0];
	}
	public function getComment($link, $session)
	{
		// $url = 'https://www.instagram.com/p/' . $link . '/';
		$url = '' . $link . '';
		$header = array(
			'origin: https://www.instagram.com',
			'referer: https://www.instagram.com/',
			'User-Agent: ' . $session['useragent'],
			'X-CSRFToken: ' . $session['csrftoken'],
			'Cookie: ig_did=' . $session['ig_did'] . '; mid=' . $session['mid'] . '; csrftoken=' . $session['csrftoken'] . '; ds_user_id=' . $session['ds_user_id'] . '; sessionid=' . $session['sessionid']
		);
		$post = $this->curl($url, 1, $header);
		// $data_ouput = $this->client_fungsi->getStr($post, "window.__additionalDataLoaded('/p/$link/',", ");</script>");
		$data_ouput = $this->getStr($post, '{"graphql":', "});</script>");
		$data_array = json_decode($data_ouput);
		$result = $data_array;
		foreach ($result as $items) {
			$data = array(
				'status' => 'success',
				'id' => $items->id,
				'commentid' => $items->edge_media_to_parent_comment->edges['0']->node->id,
				'username' => $items->edge_media_to_parent_comment->edges['0']->node->owner->username,
			);
			if ($data['username'] != $_SESSION['username']) {
				$data = array(
					'status' => 'success',
					'id' => $items->id,
					'commentid' => $items->edge_media_to_parent_comment->edges['1']->node->id,
					'username' => $items->edge_media_to_parent_comment->edges['1']->node->owner->username,
				);
			}
		}
		return $data;
	}
	public function getProfile($session)
	{
		$url = 'https://www.instagram.com/accounts/edit/?__a=1';
		$header = array(
			'Cookie: sessionid=' . $session['sessionid'] . ';',
		);
		$get = $this->curl($url, 0, $header);
		$first_name = $this->getStr($get, '{"first_name":"', '","');
		$last_name = $this->getStr($get, '"last_name":"', '","');
		$email = $this->getStr($get, 'email":"', '","');
		$username = $this->getStr($get, '"username":"', '","');
		$bio = $this->getStr($get, 'biography":"', '","');
		if (strpos($get, 'Location: https://www.instagram.com/challenge/')) {
			$data = array(
				'status' => 'challenge',
			);
		} elseif (strpos($get, '301')) {
			$data = array(
				'status' => 'dead',
			);
		} elseif (strpos($get, '200 OK')) {
			$data = array(
				'first_name' => $first_name,
				'last_name' => $last_name,
				'email' => $email,
				'username' => $username,
				'bio' => $bio,
				'status' => 'success',
			);
		} else {
			$data = array(
				'status' => 'error',
			);
		}
		return $data;
	}
	public function getArtikel($link, $session)
	{
		$url = '' . $link . '';
		$header = array(
			'origin: https://www.instagram.com',
			'referer: https://www.instagram.com/',
			'User-Agent: ' . $session['useragent'],
			'X-CSRFToken: ' . $session['csrftoken'],
			'Cookie: ig_did=' . $session['ig_did'] . '; mid=' . $session['mid'] . '; csrftoken=' . $session['csrftoken'] . '; ds_user_id=' . $session['ds_user_id'] . '; sessionid=' . $session['sessionid']
		);
		$post = $this->curl($url, 1, $header);
		$data_ouputs = $this->getStr($post, '<script type="text/javascript">window._sharedData = ', ';</script>');
		$data_arrays = json_decode($data_ouputs);
		$results = $data_arrays->config->viewer;
		$data_ouput = $this->getStr($post, '{"graphql":', "});</script>");
		$data_array = json_decode($data_ouput);
		$result = $data_array;
		foreach ($result as $items) {
			$data = array(
				'status' => 'success',
				'user' => $items->owner->id,
				'id' => $items->id,
			);
		}
		return $data;
	}
	public function commentlike($id, $session)
	{
		if (isset($id)) {
			$guid = $this->GenerateGuid();
			// $url = 'https://www.instagram.com/web/comments/like/' . $id . '/';
			$url = 'https://i.instagram.com/api/v1/media/' . $id . '/comment_like/';

			$header = array(
				'Origin: https://www.instagram.com',
				'Referer: https://www.instagram.com/',
				'User-Agent: ' . $session['useragent'],
				'X-CSRFToken: ' . $session['csrftoken'],
				'Cookie: ig_did=' . $session['ig_did'] . '; mid=' . $session['mid'] . '; csrftoken=' . $session['csrftoken'] . '; ds_user_id=' . $session['ds_user_id'] . '; sessionid=' . $session['sessionid']
			);
			$device_id = "android-" . $guid;
			$data = '{"device_id":"' . $device_id . '","guid":"' . $guid . '","uid":"' . $session['ds_user_id'] . '","module_name":"feed_timeline","d":"0","source_type":"5","filter_type":"0","extra":"{}","Content-Type":"application/x-www-form-urlencoded; charset=UTF-8"}';
			$sig = $this->GenerateSignature($data);
			$body = 'signed_body=' . $sig . '.' . urlencode($data) . '&ig_sig_key_version=4';
			// $body = 'ig_sig_key_version=4&signed_body=' . rand(100, 1000) . '';
			$post = $this->curlNoHeader($url, $body, $header, true);
			$data = (strpos($post, "Error")) ? array("media" => $id, "action" => "like", "status" => "error") : array("media" => $id, "action" => "like", "status" => "success");
		} else {
			$data = array("media" => $id, "status" => "error", "details" => "media not found");
		}
		return $data;
	}
	public function like($id, $session)
	{
		if (isset($id)) {
			$guid = $this->GenerateGuid();
			// $url = 'https://www.instagram.com/web/likes/' . $id . '/like/';
			$url = 'https://i.instagram.com/api/v1/media/' . $id . '/like/';
			$header = array(
				'Origin: https://www.instagram.com',
				'Referer: https://www.instagram.com/',
				'User-Agent: ' . $session['useragent'],
				'X-CSRFToken: ' . $session['csrftoken'],
				'Cookie: ig_did=' . $session['ig_did'] . '; mid=' . $session['mid'] . '; csrftoken=' . $session['csrftoken'] . '; ds_user_id=' . $session['ds_user_id'] . '; sessionid=' . $session['sessionid']
			);
			$device_id = "android-" . $guid;
			$data = '{"device_id":"' . $device_id . '","guid":"' . $guid . '","uid":"' . $session['ds_user_id'] . '","module_name":"feed_timeline","d":"0","source_type":"5","filter_type":"0","extra":"{}","Content-Type":"application/x-www-form-urlencoded; charset=UTF-8"}';
			$sig = $this->GenerateSignature($data);
			$body = 'signed_body=' . $sig . '.' . urlencode($data) . '&ig_sig_key_version=4';
			// $body = 'ig_sig_key_version=4&signed_body=' . rand(100, 1000) . '';
			$post = $this->curlNoHeader($url, $body, $header, true);
			$data = (strpos($post, "Error")) ? array("media" => $id, "action" => "like", "status" => "error") : array("media" => $id, "action" => "like", "status" => "success");
		} else {
			$data = array("media" => $id, "status" => "error", "details" => "media not found");
		}
		return $data;
	}
	public function like_login($id, $session)
	{
		if (isset($id)) {
			$url = 'https://www.instagram.com/web/likes/' . $id . '/like/';
			$header = array(
				'origin: https://www.instagram.com',
				'referer: https://www.instagram.com/',
				'User-Agent: ' . $session['useragent'],
				'X-CSRFToken: ' . $session['csrftoken'],
				'Cookie: ig_did=' . $session['ig_did'] . '; mid=' . $session['mid'] . '; csrftoken=' . $session['csrftoken'] . '; ds_user_id=' . $session['ds_user_id'] . '; sessionid=' . $session['sessionid']
			);
			$post = $this->curl($url, 1, $header);
			$data = (strpos($post, "Error")) ? array("media" => $id, "action" => "like", "status" => "error") : array("media" => $id, "action" => "like", "status" => "success");
		} else {
			$data = array("media" => $id, "status" => "error", "details" => "media not found");
		}
		return $data;
	}
	public function follow($username, $session)
	{
		if (isset($username)) {
			$guid = $this->GenerateGuid();
			$id = $username;
			// $url = 'https://www.instagram.com/web/friendships/' . $id . '/follow/';
			$url = 'https://i.instagram.com/api/v1/friendships/create/' . $id . '/';
			$header = array(
				'origin: https://www.instagram.com',
				'referer: https://www.instagram.com/',
				'User-Agent: ' . $session['useragent'],
				'X-CSRFToken: ' . $session['csrftoken'],
				'Cookie: ig_did=' . $session['ig_did'] . '; mid=' . $session['mid'] . '; csrftoken=' . $session['csrftoken'] . '; ds_user_id=' . $session['ds_user_id'] . '; sessionid=' . $session['sessionid']
			);
			$device_id = "android-" . $guid;
			$data = '{"device_id":"' . $device_id . '","guid":"' . $guid . '","uid":"' . $session['ds_user_id'] . '","module_name":"feed_timeline","d":"0","source_type":"5","filter_type":"0","extra":"{}","Content-Type":"application/x-www-form-urlencoded; charset=UTF-8"}';
			$sig = $this->GenerateSignature($data);
			$body = 'signed_body=' . $sig . '.' . urlencode($data) . '&ig_sig_key_version=4';
			// $body = 'ig_sig_key_version=4&signed_body=' . rand(100, 1000) . '';
			$post = $this->curl($url, $body, $header, true);
			if (strpos($post, 'Location: https://www.instagram.com/challenge/')) {
				$data = array(
					'username' => $username,
					'action' => 'follow',
					'status' => 'error',
				);
			} elseif (strpos($post, '"status":"ok"')) {
				$data = array(
					'username' => $username,
					'action' => 'follow',
					'status' => 'success',
				);
			} else {
				$data = array(
					'username' => $username,
					'action' => 'follow',
					'status' => 'error',
				);
			}
		} else {
			$data = array(
				'status' => 'error',
				'username' => $username,
				'action' => 'follow',
				'details' => 'username not found',
			);
		}
		return $data;
	}

	public function comment($id, $session, $text)
	{
		// $url = 'https://www.instagram.com/web/comments/' . $id . '/add/';
		$url = 'https://i.instagram.com/api/v1/media/' . $id . '/comment/';
		$guid = $this->GenerateGuid();
		$header = array(
			'origin: https://www.instagram.com',
			'referer: https://www.instagram.com/',
			'User-Agent: ' . $session['useragent'],
			'X-CSRFToken: ' . $session['csrftoken'],
			'Cookie: ig_did=' . $session['ig_did'] . '; mid=' . $session['mid'] . '; csrftoken=' . $session['csrftoken'] . '; ds_user_id=' . $session['ds_user_id'] . '; sessionid=' . $session['sessionid']
		);
		// $body = 'comment_text='.$text.'&replied_to_comment_id=17854158589878259';
		// $body = 'comment_text=' . $text . '&replied_to_comment_id=';
		$device_id = "android-" . $guid;
		$data = '{"device_id":"' . $device_id . '","guid":"' . $guid . '","uid":"' . $session['ds_user_id'] . '", "comment_text": "' . $text . '","module_name":"feed_timeline","d":"0","source_type":"5","filter_type":"0","extra":"{}","Content-Type":"application/x-www-form-urlencoded; charset=UTF-8"}';
		$sig = $this->GenerateSignature($data);
		$body = 'signed_body=' . $sig . '.' . urlencode($data) . '&ig_sig_key_version=4';
		$post = $this->curlNoHeader($url, $body, $header, true);
		$result = json_decode($post);

		if (strpos($post, 'Please wait')) {
			$data = array(
				'action' => 'comment',
				'status' => 'error',
				'details' => 'Please wait a few minutes before you try again'
			);
		} elseif (strpos($post, '"status":"ok"')) {
			$data = array(
				'action' => 'comment',
				'status' => 'success',
			);
		} else {
			$data = array(
				'action' => 'comment',
				'status' => 'error',
				'details' => $post
			);
		}
		return $data;
	}
}
