<?php
	require_once '../app/core/Db.php';
	class MRegister {
		
		public function addAccount($email, $username, $password) {
			$sql = "INSERT INTO accounts (email, username, password, created_at, updated_at) VALUES (:email, :username, :password, :created_at, :updated_at)";
			$register_request = DB::getConnection()->prepare($sql);
			return $register_request -> execute([
				'email' => $email,
				'username' => $username,
				'password' => $password,
				'created_at' => date('Y-m-d H:i:s'),
				'updated_at' => date('Y-m-d H:i:s')

			]);
		}
	}


?>