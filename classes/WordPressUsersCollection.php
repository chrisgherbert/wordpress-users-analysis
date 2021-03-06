<?php

class WordPressUsersCollection extends WordPressDataCollection {

	public function app_url_slug(){
		return 'users';
	}

	public function items(){

		if ($this->data){

			return array_map(function($item){
				return new WordPressUser($item);
			}, $this->data);

		}

	}

}