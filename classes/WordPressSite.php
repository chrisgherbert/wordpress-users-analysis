<?php

use Curl\Curl;

class WordPressSite {

	public $url;
	public $site;
	protected $users;

	public function __construct($url){
		$this->url = $url;
		$this->site = $this->get_site_obj();
	}

	public function users(){

		if (isset($this->users)){
			return $this->users;
		}

		$curl = self::get_curl();

		error_log($this->get_users_endpoint_url());

		$curl->get($this->get_users_endpoint_url());

		if ($curl->error) {
			error_log('Error: ' . $curl->error_code . ': ' . $curl->error_message);
		}
		else {

			$data = json_decode($curl->response);

			$users = array();

			foreach ($data as $item){
				$users[] = new WordPressUser($item);
			}

			$this->users = $users;

			return $this->users;

		}

	}

	public function get_comments_endpoint_url(){

		if ($this->site){
			return $this->get_index_url() . 'wp/v2/comments';
		}

	}

	public function get_users_endpoint_url(){

		if ($this->get_index_url()){
			$users_endpoint = $this->get_index_url() . 'wp/v2/users';
		}
		else {
			$parts = parse_url($this->url);
			$users_endpoint = $parts['scheme'] . '://' . $parts['host'] . '/wp-json/wp/v2/users';
		}

		return $users_endpoint;

	}

	public function name(){

		if ($this->site){
			return $this->site->getName();
		}

	}

	public function description(){

		if ($this->site){
			return $this->site->getDescription();
		}

	}

	public function url(){

		if ($this->site){
			return $this->site->getURL();
		}

	}

	public function get_index_url(){

		if ($this->site){
			return $this->site->getIndexURL() ?? false;
		}

	}

	public function get_site_obj(){

		if (isset($this->site)){
			return $this->site;
		}

		$site = \WordPress\Discovery\discover($this->url);

		$this->site = $site;

		return $this->site;

	}

	////////////
	// Static //
	////////////

	protected static function get_curl(){

		$curl = new Curl;
		$curl->setUserAgent('Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13) AppleWebKit/604.1.38 (KHTML, like Gecko) Version/11.0 Safari/604.1.38');
		$curl->setOpt(CURLOPT_SSL_VERIFYHOST, 0);
		$curl->setOpt(CURLOPT_SSL_VERIFYPEER, 0);

		return $curl;

	}

}