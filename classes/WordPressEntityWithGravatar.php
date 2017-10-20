<?php

abstract class WordPressEntityWithGravatar extends WordPressEntity {

	public $reversed_email;

	abstract protected function get_avatar_urls_key();

	public function avatar($size = 96){

		$key = $this->get_avatar_urls_key();

		$urls = $this->get_data_item($key);

		if (isset($urls->{'24'})){

			$url = $urls->{'24'};

			$url = str_replace('s=24', 's=' . $size, $url);

			return $url;

		}

	}

	public function email_hash(){

		$url = $this->avatar();

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

	public function email(){

		if (isset($this->reversed_email)){
			return $this->reversed_email;
		}

		$hash = $this->email_hash();

		if (!$hash){
			return false;
		}

		$email = EmailHashReverser::reverse($hash);

		$this->reversed_email = $email;

		return $this->reversed_email;

	}

}