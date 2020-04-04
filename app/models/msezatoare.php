<?php
	
	define ('DB_HOST', 'localhost');
	define ('DB_NAME', 'sezatoare');
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

	class MSezatoare {
		
		public function adaugaMesaj($utilizator, $mesaj) {
			$sql = "INSERT INTO mesaje (utilizator, mesaj, created_at, updated_at) VALUES (:utilizator, :mesaj, :created_at, :updated_at)";
			$cerere = BD::obtine_conexiune()->prepare($sql);
			return $cerere -> execute([
				'utilizator' => $utilizator,
				'mesaj' => $mesaj,
				'created_at' => date('Y-m-d H:i:s'),
				'updated_at' => date('Y-m-d H:i:s')

			]);
		}

		public function obtineMesaje(){
			$sql = "SELECT * FROM mesaje";
			$cerere = BD::obtine_conexiune()->prepare($sql);
			$cerere->execute();
			return $cerere->fetchAll();
		}

		public function modificaMesaj($id, $mesaj){
			$sql = "UPDATE mesaje SET mesaj = :mesaj, updated_at = :updated_at WHERE id = :id";
			$cerere = BD::obtine_conexiune()->prepare($sql);
			return $cerere -> execute([
				'mesaj' => $mesaj,
				'updated_at' => date('Y-m-d H:i:s'),
				'id' => $id
			]);
		}

		public function stergeMesaj($id){
			$sql = "DELETE FROM mesaje WHERE id = ?";
			$cerere = BD::obtine_conexiune()->prepare($sql);
			return $cerere -> execute([$id]);
		}
	}


?>