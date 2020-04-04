<?php

	define ('DB_HOST', 'localhost');
	define ('DB_NAME', 'stol_database');
	define ('DB_USER', 'stoluser');
	define ('DB_PASS', 'stoluser');
	
	class BD {
		private static $conexiune_bd = NULL;

		public static function obtine_conexiune() {

			if(is_null(self::$conexiune_bd)) {
				self::$conexiune_bd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);
			}
			return self::$conexiune_bd;
		}
	}

	class MRegister {
		
		public function addAccount($email, $username, $password) {
			$sql = "INSERT INTO accounts (email, username, password, created_at, updated_at) VALUES (:email, :username, :password, :created_at, :updated_at)";
			$cerere = BD::obtine_conexiune()->prepare($sql);
			return $cerere -> execute([
				'email' => $email,
				'username' => $username,
				'password' => $password,
				'created_at' => date('Y-m-d H:i:s'),
				'updated_at' => date('Y-m-d H:i:s')

			]);
		}
	}


?>