<?php

class WordPressComment extends WordPressEntityWithGravatar {

	public $reversed_email;

	public function post_id(){
		return $this->get_data_item('post');
	}

	public function parent_id(){
		return $this->get_data_item('parent');
	}

	public function author_name(){
		return $this->get_data_item('author_name');
	}

	public function author_url(){
		return $this->get_data_item('author_url');
	}

	public function date(){
		return $this->get_data_item('date');
	}

	public function content(){
		return $this->get_data_item('content')->rendered ?? false;
	}

	public function link(){
		return $this->get_data_item('link');
	}

	///////////////
	// Protected //
	///////////////

	protected function get_avatar_urls_key(){
		return 'author_avatar_urls';
	}

}