<?php

use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;

class EmailHashReverser {

	public $hash;

	public function __construct($hash){
		$this->hash = $hash;
	}

	public function reverse_hash(){

		$db = self::get_db_instance();

		$statement = $db->prepare("SELECT emails FROM emails WHERE hash = :hash");

		$statement->execute(['hash' => $this->hash]);

		$results = $statement->fetch();

		return $results['emails'] ?? false;

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

			$name = getenv('EMAIL_HASH_DB_NAME');
			$host = getenv('EMAIL_HASH_DB_HOST');
			$user = getenv('EMAIL_HASH_DB_USER');
			$pass = getenv('EMAIL_HASH_DB_PASS');
			$dsn = "mysql:dbname=$name; host=$host";

			$options = [
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
				PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
			];

			$db = new PDO($dsn, $user, $pass, $options);

			$emails_hash_db = $db;

		}

		return $emails_hash_db;

	}



}