<?php

use Curl\Curl;

class WordPressSite {

	public $url;
	public $site;
	protected $users_collection;

	public function __construct($url){
		$this->url = $url;
		$this->site = $this->get_site_obj();
	}

	public function get_comments_endpoint_url(){

		if ($this->get_index_url()){
			$endpoint = $this->get_index_url() . 'wp/v2/comments';
		}
		else {
			$parts = parse_url($this->url);
			$endpoint = $parts['scheme'] . '://' . $parts['host'] . '/wp-json/wp/v2/comments';
		}

		return $endpoint;

	}

	public function get_users_endpoint_url(){

		if ($this->get_index_url()){
			$endpoint = $this->get_index_url() . 'wp/v2/users';
		}
		else {
			$parts = parse_url($this->url);
			$endpoint = $parts['scheme'] . '://' . $parts['host'] . '/wp-json/wp/v2/users';
		}

		return $endpoint;

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

		// Silencing errors because the WordPress discovery tool is pretty sloppy. Tsk.
		$site = @\WordPress\Discovery\discover($this->url);

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