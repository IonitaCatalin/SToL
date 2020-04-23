<?php
	require_once '../app/core/Db.php';
	require_once '../app/core/Exceptions/CredentialsExceptions.php';

	class MProfile {

		public function insertAuthToken($data, $id, $service)
		{
			array_key_exists('expires_in', $data) ? $expires = $data['expires_in'] : $expires = null; // pt dropbox nu expira, trebuie revocate
			array_key_exists('refresh_token', $data) ? $refresh_token = $data['refresh_token'] : $refresh_token = null; // dropbox nu are asa ceva, sunt permanente pana la revocare
			$access_token=$data['access_token'];	
			$user_id=$id;
			switch ($service) {
				case 'onedrive':
					$sql = "INSERT INTO onedrive_service (user_id,refresh_token,access_token,expires_in,generated_at) VALUES (:id, :refresh, :access, :expires,SYSDATE())";
					break;
				case 'googledrive':
					$sql = "INSERT INTO googledrive_service (user_id,refresh_token,access_token,expires_in,generated_at) VALUES (:id, :refresh, :access, :expires,SYSDATE())";
					break;
				case 'dropbox':
					$sql = "INSERT INTO dropbox_service (user_id, access_token) VALUES (:id, :access)";
					break;
			}
			$insert_request = DB::getConnection()->prepare($sql);

			if( in_array($service, array('onedrive', 'googledrive')) )
				return $insert_request -> execute([
					'id' => $user_id,
					'refresh' => $refresh_token,
					'access' => $access_token,
					'expires' => $expires,
				]);
			else
				return $insert_request -> execute([
					'id' => $user_id,
					'access' => $access_token
				]);
		}

		private function isOneDriveAuthorized($id)
		{
			$get_onedrive_query = "SELECT user_id FROM onedrive_service WHERE user_id = ${id}";
			$get_onedrive_stmt = DB::getConnection()->prepare($get_onedrive_query);
			$get_onedrive_stmt->execute();
			if($get_onedrive_stmt->rowCount() > 0)
				return true;
			else
				return false;
		}

		private function isGoogleDriveAuthorized($id)
		{
			$get_googledrive_query = "SELECT user_id FROM googledrive_service WHERE user_id = ${id}";
			$get_googledrive_stmt = DB::getConnection()->prepare($get_googledrive_query);
			$get_googledrive_stmt->execute();
			if($get_googledrive_stmt->rowCount()>0)
				return true;
			else
				return false;
		}

		private function isDropboxAuthorized($id)
		{
			$get_dropbox_query = "SELECT user_id FROM dropbox_service WHERE user_id = ${id}";
			$get_dropbox_stmt = DB::getConnection()->prepare($get_dropbox_query);
			$get_dropbox_stmt->execute();
			if($get_dropbox_stmt->rowCount()>0)
				return true;
			else
				return false;
		}

		public function getAccessToken($id, $service)
		{
			$sql = '';
			switch ($service) {
				case 'onedrive':
					$sql = "SELECT access_token FROM onedrive_service WHERE user_id = ${id}";
					break;
				case 'googledrive':
					$sql = "SELECT access_token FROM googledrive_service WHERE user_id = ${id}";
					break;
				case 'dropbox':
					$sql = "SELECT access_token FROM dropbox_service WHERE user_id = ${id}";
					break;
			}
			$stmt = DB::getConnection()->prepare($sql);
			$stmt->execute();
			if($stmt->rowCount() > 0) {
				$result = $stmt->fetch();
				return $result['access_token'];
			}
			else
				echo 'Id-ul nu are niciun token asociat';
		}


		public function getUserDataArray($user_id)
		{
			$result_array = array();

			$get_query = "SELECT username, email FROM accounts WHERE id = ${user_id}";
			$get_stmt=DB::getConnection()->prepare($get_query);
			$get_stmt->execute();
			$result_array += $get_stmt->fetch(PDO::FETCH_ASSOC);

			$onedrive_status=$this->isOneDriveAuthorized($user_id);
			$google_status=$this->isGoogleDriveAuthorized($user_id);
			$dropbox_status=$this->isDropboxAuthorized($user_id);

			$result_array['onedrive'] = $onedrive_status;
			$result_array['googledrive'] = $google_status;
			$result_array['dropbox'] = $dropbox_status;
			return $result_array;
		}

		public function checkExistingUsername($username) {
			$check_username = $username;
			$sql = "SELECT id  FROM accounts WHERE username = :username";
			$check_username_stmt = DB::getConnection()->prepare($sql);
			$check_username_stmt -> execute([
				'username' => $check_username
			]);

			if($check_username_stmt->rowCount() != 0)
				return true;
			else
				return false;
		}

		public function updateUsername($username,$id)
		{

			if($this->checkExistingUsername($username))
			{
				throw new UsernameTakenException('Username is already taken!');
			}
			else
			{
				$sql="UPDATE accounts SET username=:username,updated_at=:updated WHERE id=:id";
				$update_username_stmt=DB::getConnection()->prepare($sql);
				$update_username_stmt->execute([
						'username'=>htmlentities($username),
						'updated'=>date("Y-m-d H:i:s"),
						'id' => $id
				]);
			}
		}

		public function updatePassword($oldpass,$newpass,$id)
		{
			$get_sql="SELECT password FROM ACCOUNTS WHERE id=:userid";
			$get_pass_stmt=DB::getConnection()->prepare($get_sql);
			$get_pass_stmt->execute([
				'userid'=>$id
			]);
			$old_password=$get_pass_stmt->fetch(PDO::FETCH_ASSOC);
			if(strcmp($old_password['password'],$oldpass)==0)
			{
				$update_sql="UPDATE accounts SET password=:newpass WHERE id=:userid";
				$update_pass_stmt=DB::getConnection()->prepare($update_sql);
				$update_pass_stmt->execute([
					'newpass'=>$newpass,
					'userid'=>$id
				]);
			}
			else
			{
				throw new IncorrectPasswordException('The old password introduced is incorrect!');
			}
		}

		public function invalidateService($id,$service)
		{
			$delete_sql = '';
			switch ($service) {
				case 'onedrive':
					$delete_sql = "DELETE FROM onedrive_service WHERE user_id = ${id}";
					break;
				case 'googledrive':
					$delete_sql = "DELETE FROM googledrive_service WHERE user_id = ${id}";
					break;
				case 'dropbox':
					$delete_sql="DELETE FROM dropbox_service WHERE user_id=${id}";
					break;

			}
			$stmt = DB::getConnection()->prepare($delete_sql);
			$stmt->execute();
		}
	}
	

?>