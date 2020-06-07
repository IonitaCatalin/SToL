<?php

	class MRegister {
		private $user_id;
		public function __construct()
		{
			$bytes=random_bytes(16);
			$this->user_id=bin2hex($bytes);
		}
		
		public function addAccount($email, $username, $password) {
			$sql = "INSERT INTO accounts (id,email, username, password, created_at, updated_at) VALUES (:id,:email, :username, :password, :created_at, :updated_at)";
			$register_request = DB::getConnection()->prepare($sql);
			$register_request -> execute([
				'id'=>$this->user_id,
				'email' => $email,
				'username' => $username,
				'password' => $password,
				'created_at' => date('Y-m-d H:i:s'),
				'updated_at' => date('Y-m-d H:i:s')

			]);
			$bytes=random_bytes(16);
			$item_id=bin2hex($bytes);
			$insert_item_sql="INSERT INTO ITEMS (user_id,item_id,content_type) VALUES (:user_id,:item_id,'folder')";
			$insert_item_stmt=DB::getConnection()->prepare($insert_item_sql);
			$insert_item_stmt->execute([
				'user_id'=>$this->user_id,
				'item_id'=>$item_id
			]);

			$insert_root_folder_sql="INSERT INTO FOLDERS(item_id,parent_id,name,created_at) VALUES (:item_id,NULL,'root',:created_at)";
			$insert_root_folder_stmt=DB::getConnection()->prepare($insert_root_folder_sql);
			$insert_root_folder_stmt->execute([
				'item_id'=>$item_id,
				'created_at'=>date('Y-m-d H:i:s')
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