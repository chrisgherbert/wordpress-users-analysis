<?php

use Curl\Curl;

class WordPressUsers {

	public $url;

	public function __construct($url){
		$this->url = $url;
	}

	public function get_data(){

		$curl = $this->get_curl();

		$curl->get($this->get_users_rest_endpoint());

		if ($curl->error) {
			throw new \Exception('Error: ' . $curl->error_code . ': ' . $curl->error_message);
		}
		else {

			$data = json_decode($curl->response);

			$processed_data = $this->process_users_data($data);

			return $processed_data;

		}

	}

	protected function process_users_data($data){

		if ($data){

			$data = array_map(function($item){

				if (isset($item->avatar_urls->{'24'})){

					$hash = self::extract_hash_from_gravatar_url($item->avatar_urls->{'24'});

					$email = EmailHashReverser::reverse($hash);

					$item->email = $email;

				}

				return $item;

			}, $data);

		}

		return $data;

	}

	protected function get_users_rest_endpoint(){

		$url = $this->url;

		$endpoint = self::untrailingslashit($url) . '/wp-json/wp/v2/users?per_page=100';

		return $endpoint;

	}

	protected static function get_curl(){

		$curl = new Curl;
		$curl->setUserAgent('Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13) AppleWebKit/604.1.38 (KHTML, like Gecko) Version/11.0 Safari/604.1.38');
		$curl->setOpt(CURLOPT_SSL_VERIFYHOST, 0);
		$curl->setOpt(CURLOPT_SSL_VERIFYPEER, 0);
		$curl->setOpt(CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_0);
		$curl->setOpt(CURLOPT_SSL_CIPHER_LIST, 'TLSv1');

		return $curl;

	}

	////////////
	// Static //
	////////////

	public static function extract_hash_from_gravatar_url($url){

		if (!$url){
			return false;
		}

		$parts = parse_url($url);

		$path = trim($parts['path'], '/');

		$path_parts = explode('/', $path);

		if (isset($path_parts[1])){
			return $path_parts[1];
		}

	}

	public static function untrailingslashit( $string ) {
		return rtrim( $string, '/\\' );
	}

}