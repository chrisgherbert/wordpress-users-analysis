<?php

use Curl\Curl;

class WordPressComments {

	public $url;

	public function __construct($url){
		$this->url = $url;
	}

	public function get_data(){

		$curl = new Curl;
		$curl->setUserAgent('Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13) AppleWebKit/604.1.38 (KHTML, like Gecko) Version/11.0 Safari/604.1.38');
		$curl->get($this->get_comments_rest_endpoint());

		if ($curl->error) {
			error_log($curl->error_code);
		}
		else {
			return  json_decode($curl->response);
		}

	}

	protected function get_comments_rest_endpoint(){

		$url = $this->url;

		$endpoint = self::untrailingslashit($url) . '/wp-json/wp/v2/comments?per_page=100';

		return $endpoint;

	}

	////////////
	// Static //
	////////////

	public static function untrailingslashit( $string ) {
		return rtrim( $string, '/\\' );
	}

}