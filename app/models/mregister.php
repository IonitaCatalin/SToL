<?php

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

		public function checkExistingEmail($email) {
			$check_email = $email;
			$sql = "SELECT 'abc' FROM accounts WHERE email = :email";
			$check_email_stmt = DB::getConnection()->prepare($sql);
			$check_email_stmt -> execute([
				'email' => $check_email
			]);

			if($check_email_stmt->rowCount() != 0)
				return true;
			else
				return false;
		}

		public function checkExistingUsername($username) {
			$check_username = $username;
			$sql = "SELECT 'abc' FROM accounts WHERE username = :username";
			$check_username_stmt = DB::getConnection()->prepare($sql);
			$check_username_stmt -> execute([
				'username' => $check_username
			]);

			if($check_username_stmt->rowCount() != 0)
				return true;
			else
				return false;
		}
	}


?>