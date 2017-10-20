<?php

class WordPressUser extends WordPressEntityWithGravatar {

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

	///////////////
	// Protected //
	///////////////

	protected function get_avatar_urls_key(){
		return 'avatar_urls';
	}

}