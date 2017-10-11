<?php

use Medoo\Medoo;

class EmailHashReverser {

	public $hash;

	public function __construct($hash){
		$this->hash = $hash;
	}

	public function reverse_hash(){

		$db = self::get_db_instance();

		$data = $db->select(
			'emails',
			['emails'],
			[
				'hash[=]' => $this->hash
			]
		);

		return $data[0]['emails'] ?? false;

	}

	////////////
	// Static //
	////////////

	public static function reverse($hash){

		$reverser = new self($hash);

		return $reverser->reverse_hash();

	}

	protected static function get_db_instance(){

		global $emails_hash_db;

		if (!$emails_hash_db){

			$emails_hash_db = new Medoo([
				'database_type' => 'mysql',
				'database_name' => getenv('EMAIL_HASH_DB_NAME'),
				'server' => getenv('EMAIL_HASH_DB_HOST'),
				'username' => getenv('EMAIL_HASH_DB_USER'),
				'password' => getenv('EMAIL_HASH_DB_PASS')
			]);

		}

		return $emails_hash_db;

	}

}