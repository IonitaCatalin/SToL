<?php
	require_once '../app/core/Db.php';

	class MProfile {

		public function insertAuthToken($data,$id,$service)
		{
			$expires=$data['expires_in'];
			array_key_exists('refresh_token', $data) ? $refresh_token = $data['refresh_token'] : $refresh_token = null; // cei de la google sunt zgarciti cu refresh token-urile
			$access_token=$data['access_token'];	
			$user_id=$id;
			switch ($service) {
				case 'onedrive':
					$sql = "INSERT INTO onedrive_service (user_id,refresh_token,access_token,expires_in) VALUES (:id, :refresh, :access, :expires)";
					break;
				case 'googledrive':
					$sql = "INSERT INTO googledrive_service (user_id,refresh_token,access_token,expires_in) VALUES (:id, :refresh, :access, :expires)";
					break;
			}
			$insert_request = DB::getConnection()->prepare($sql);
			return $insert_request -> execute([
				'id' => $user_id,
				'refresh' => $refresh_token,
				'access' => $access_token,
				'expires' => $expires
			]);
		}
		private function isOneDriveAuthorized($id)
		{
			$get_onedrive_query="SELECT user_id FROM onedrive_service WHERE user_id=${id}";
			$get_onedrive_stmt=DB::getConnection()->prepare($get_onedrive_query);
			$get_onedrive_stmt->execute();
			if($get_onedrive_stmt->rowCount()>0)
			{
				return true;
			}
			else return false;

		}
		private function isGoogleDriveAuthorized($id)
		{
			$get_onedrive_query="SELECT user_id FROM googledrive_service WHERE user_id=${id}";
			$get_onedrive_stmt=DB::getConnection()->prepare($get_onedrive_query);
			$get_onedrive_stmt->execute();
			if($get_onedrive_stmt->rowCount()>0)
			{
				return true;
			}
			else return false;

		}
		public function getUserDataArray($user_id)
		{
			$result_array=array();
			$get_query="SELECT username,email FROM accounts WHERE id=${user_id}";
			
			$get_googledrive_query="SELECT user_id FROM googledrive_service WHERE user_id=${user_id}";
			$get_dropbox_query="SELECT user_id FROM onedriv_service WHERE user_id=${user_id}";
				$get_stmt=DB::getConnection()->prepare($get_query);
				$get_googledrive_stmt=DB::getConnection()->prepare($get_googledrive_query);
				$get_stmt->execute();
				$result_array+=$get_stmt->fetch(PDO::FETCH_ASSOC);
				$onedrive_status=$this->isOneDriveAuthorized($user_id);
				$google_status=$this->isGoogleDriveAuthorized($user_id);
				$dropbox_status=false;
				$result_array['onedrive']=$onedrive_status;
				$result_array['googledrive']=$google_status;
				$result_array['dropbox']=$dropbox_status;
				return $result_array;
		}
		

	}

?>