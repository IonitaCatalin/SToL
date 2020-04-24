<?php
	require_once '../app/core/Db.php';
	require_once '../app/core/Onedrive/Onedrive.php';
	require_once '../app/core/Onedrive/OnedriveException.php';
	require_once '../app/core/GDrive/Googledrive.php';
	require_once '../app/core/Dropbox/Dropbox.php';

	// probabil metode ce apeleaza baza de date si functionalitati din Onedrive.php, Googledrive.php si Dropbox.php
	class MFiles {
		// public function getRefreshToken($id, $service)
		// {
		// 	$sql = '';
		// 	switch ($service) {
		// 		case 'onedrive':
		// 			$sql = "SELECT refresh_token FROM onedrive_service WHERE user_id = ${id}";
		// 			break;
		// 		case 'googledrive':
		// 			$sql = "SELECT refresh_token FROM googledrive_service WHERE user_id = ${id}";
		// 			break;
		// 	}
		// 	$stmt = DB::getConnection()->prepare($sql);
		// 	$stmt->execute();
		// 	if($stmt->rowCount() > 0) {
		// 		$result = $stmt->fetch();
		// 		return $result['refresh_token'];
		// 	}
		// 	else
		// 		echo 'Id-ul nu are niciun refresh token asociat';
		// }
		public function getAccessToken($service,$id)
		{
			switch($service)
			{
				case 'onedrive':
				{
					$get_expire_date_sql="SELECT TIMESTAMPDIFF(SECOND,GENERATED_AT,SYSDATE()) AS 'time_passed',expires_in FROM onedrive_service WHERE user_id=:userid";
					$get_expire_date_stmt=DB::getConnection()->prepare($get_expire_date_sql);
					$get_expire_date_stmt->execute([
						'userid'=>$id
					]);
					$result_array=$get_expire_date_stmt->fetch(PDO::FETCH_ASSOC);
					if($result_array['time_passed']>$result_array['expires_in'])
					{
						$get_refresh_token_sql="SELECT refresh_token FROM onedrive_service WHERE user_id=:userid";
						$get_refresh_tokens_stmt=DB::getConnection()->prepare($get_refresh_token_sql);
						$get_refresh_tokens_stmt->execute([
							'userid'=>$id
						]);
							$refresh_token=$get_refresh_tokens_stmt->fetch(PDO::FETCH_ASSOC)['refresh_token'];
							$refreshed_tokens=OneDriveService::renewTokens($refresh_token);
							var_dump($refreshed_tokens);
							$update_sql="UPDATE onedrive_service SET refresh_token=:refresh,access_token=:access,generated_at=SYSDATE() WHERE user_id=:userid";
							$update_stmt=DB::getConnection()->prepare($update_sql);
							$update_stmt->execute([
								'refresh'=>$refreshed_tokens['refresh_token'],
								'access'=>$refreshed_tokens['access_token'],
								'userid'=>$id
							]);

					}
					else
					{
						$get_access_token_sql="SELECT access_token FROM onedrive_service WHERE user_id=:userid";
						$get_access_token_stmt=DB::getConnection()->prepare($get_access_token_sql);
						$get_access_token_stmt->execute([
							'userid'=>$id
						]);
						$result_token=$get_access_token_stmt->fetch(PDO::FETCH_ASSOC)['access_token'];

						return $result_token;
					}
					break;
				}
				case 'googledrive':
				{
					break;
				}
				case 'dropbox':
				{
				   break;
				}
			}
		}
	}


?>