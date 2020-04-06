<?php
	define ('DB_HOST', 'localhost');
	define ('DB_NAME', 'stol_database');
	define ('DB_USER', 'stoluser');
	define ('DB_PASS', 'stoluser');
	
	class DB {
		private static $db_conn = NULL;

		public static function getConnection() {

			if(is_null(self::$db_conn)) {
				self::$db_conn = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);
			}
			return self::$db_conn;
		}
    }
    
?>