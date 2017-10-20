<?php

use Curl\Curl;

abstract class WordPressDataCollection {

	public $site_url;
	public $endpoint_url;
	public $options;
	public $per_page;
	public $page;

	public $data;
	public $headers;

	public function __construct($site_url, $endpoint_url, $page = 1, $per_page = 50){
		$this->site_url = $site_url;
		$this->endpoint_url = $endpoint_url;
		$this->page = $page;
		$this->per_page = $per_page;
		$this->make_request();
	}

	abstract public function items();

	abstract public function app_url_slug();

	public function next_prev(){

		if ($this->total_pages() > 1){

			$items = array();

			$app_url_slug = $this->app_url_slug();

			if ($this->page != 1){

				$items[] = array(
					'name' => '&larr; Previous',
					'url' => "/$app_url_slug?" . http_build_query([
						'site_url' => $this->site_url,
						'page' => $this->page - 1
					])
				);

			}

			if ($this->total_pages() != $this->page){

				$items[] = array(
					'name' => 'Next &rarr;',
					'url' => "/$app_url_slug?" . http_build_query([
						'site_url' => $this->site_url,
						'page' => $this->page + 1
					])
				);

			}

			return $items;

		}

	}

	public function pagination(){

		if ($this->total_pages() > 1){

			$pages = array();

			$app_url_slug = $this->app_url_slug();

			foreach (range(1, $this->total_pages()) as $page_num){

				$pages[] = [
					'num' => $page_num,
					'url' => "/$app_url_slug?" . http_build_query([
						'site_url' => $this->site_url,
						'page' => $page_num
					]),
					'current' => $this->current_page() == $page_num
				];

			}

			return $pages;

		}

	}

	public function current_page(){
		return $this->page ?? 1;
	}

	public function total_pages(){
		return $this->headers['X-WP-TotalPages'] ?? 1;
	}

	public function total_items(){
		return $this->headers['X-WP-Total'] ?? 0;
	}

	public function get_data_item($key){
		return $this->data[$key] ?? false;
	}

	public function get_header_item($key){
		return $this->headers[$key] ?? false;
	}

	///////////////
	// Protected //
	///////////////

	protected function make_request(){

		$curl = static::get_curl();

		$params = array(
			'per_page' => $this->per_page,
			'page' => $this->page
		);

		$request_url = $this->endpoint_url . '?' . http_build_query($params);

		$curl->get($request_url);

		if ($curl->error){
			throw new \Exception('Error: ' . $curl->errorCode . ': ' . $curl->errorMessage);
		}

		$this->data = $curl->response;
		$this->headers = $curl->responseHeaders;

		return true;

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