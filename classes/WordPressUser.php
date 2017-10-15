<?php

class WordPressUser {

	public $data;
	public $reversed_email;

	public function __construct($data){
		$this->data = $data;
	}

	public function get_data_item($key){
		return $this->data->$key ?? false;
	}

	public function id(){
		return $this->get_data_item('id');
	}

	public function url(){
		return $this->get_data_item('url');
	}

	public function description(){
		return $this->get_data_item('description');
	}

	public function link(){
		return $this->get_data_item('link');
	}

	public function slug(){
		return $this->get_data_item('slug');
	}

	public function name(){
		return $this->get_data_item('name');
	}

	public function avatar($size = 96){

		$urls = $this->get_data_item('avatar_urls');

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