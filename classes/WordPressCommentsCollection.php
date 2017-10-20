<?php

class WordPressCommentsCollection extends WordPressDataCollection {

	public function app_url_slug(){
		return 'comments';
	}

	public function items(){

		if ($this->data){

			return array_map(function($item){
				return new WordPressComment($item);
			}, $this->data);

		}

	}

}