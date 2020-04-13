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
				default:
					echo 'Cum ai ajuns aici?';
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

	}

?>