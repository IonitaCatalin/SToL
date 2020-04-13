<?php
	require_once '../app/core/Db.php';

	class MProfile {

		public function insertAuthToken($data,$id)
		{
			$expires=$data['expires_in'];
			$refresh_token=$data['refresh_token'];
			$access_token=$data['access_token'];	
			$user_id=$id;
			$sql = "INSERT INTO onedrive_service (user_id,onedrive_refresh_token,onedrive_access_token,onedrive_expires_in) VALUES (:id, :refresh, :access, :expires)";
			$insert_request = DB::getConnection()->prepare($sql);
			return $insert_request -> execute([
				'id' => $user_id,
				'refresh' => $refresh_token,
				'access' => $access_token,
				'expires' => $expires
			]);
		}

	}

?>