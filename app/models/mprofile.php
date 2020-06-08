<?php

	class MProfile {
		public function isServiceAllowed($service)
		{
			$get_service_sql="SELECT allowed FROM ALLOWED WHERE service=:service";
			$get_service_stmt=DB::getConnection()->prepare($get_service_sql);
			$get_service_stmt->execute([
				'service'=>$service
			]);
			$result_array=$get_service_stmt->fetch(PDO::FETCH_ASSOC);
			if($result_array['allowed']==1)
				return true;
			else return false;
		}
		public function insertAuthToken($data, $id, $service)
		{
			array_key_exists('expires_in', $data) ? $expires = $data['expires_in'] : $expires = null; // pt dropbox nu expira, trebuie revocate
			array_key_exists('refresh_token', $data) ? $refresh_token = $data['refresh_token'] : $refresh_token = null; // dropbox nu are asa ceva, sunt permanente pana la revocare
			$access_token=$data['access_token'];	
			$user_id=$id;
			switch ($service) {
				case 'onedrive':
					$sql = "INSERT INTO onedrive_service (user_id,refresh_token,access_token,expires_in,generated_at) VALUES (:id, :refresh, :access, :expires,:time)";
					break;
				case 'googledrive':
					$sql = "INSERT INTO googledrive_service (user_id,refresh_token,access_token,expires_in,generated_at) VALUES (:id, :refresh, :access, :expires,:time)";
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
					'time'=>date("Y-m-d H:i:s",time())
				]);
			else
				return $insert_request -> execute([
					'id' => $user_id,
					'access' => $access_token
				]);
		}

		private function isOneDriveAuthorized($id)
		{
			$get_onedrive_query = "SELECT user_id FROM onedrive_service WHERE user_id = :id";
			$get_onedrive_stmt = DB::getConnection()->prepare($get_onedrive_query);
			$get_onedrive_stmt->execute([
				'id'=>$id
			]);
			if($get_onedrive_stmt->rowCount() > 0)
				return true;
			else
				return false;
		}

		private function isGoogleDriveAuthorized($id)
		{
			$get_googledrive_query = "SELECT user_id FROM googledrive_service WHERE user_id = :id";
			$get_googledrive_stmt = DB::getConnection()->prepare($get_googledrive_query);
			$get_googledrive_stmt->execute([
				'id'=>$id
			]);
			if($get_googledrive_stmt->rowCount()>0)
				return true;
			else
				return false;
		}

		private function isDropboxAuthorized($id)
		{
			$get_dropbox_query = "SELECT user_id FROM dropbox_service WHERE user_id = :id";
			$get_dropbox_stmt = DB::getConnection()->prepare($get_dropbox_query);
			$get_dropbox_stmt->execute([
				'id'=>$id
			]);
			if($get_dropbox_stmt->rowCount()>0)
				return true;
			else
				return false;
		}

		public function getUserDataArray($user_id,$admin)
		{
			$result_array = array();
			$get_query = "SELECT username, email FROM accounts WHERE id = :user_id";
			$get_stmt=DB::getConnection()->prepare($get_query);
			$get_stmt->execute([
				'user_id'=>$user_id
			]);
			$result_array += $get_stmt->fetch(PDO::FETCH_ASSOC);

			$onedrive_status=$this->isOneDriveAuthorized($user_id);
			$google_status=$this->isGoogleDriveAuthorized($user_id);
			$dropbox_status=$this->isDropboxAuthorized($user_id);

			$result_array['onedrive'] = $onedrive_status;
			$result_array['googledrive'] = $google_status;
			$result_array['dropbox'] = $dropbox_status;
			$result_array['admin'] = $admin;
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
		public function getAccessToken($user_id,$service)
        {
            switch($service)
            {
                case 'onedrive':
                {
                    $get_onedrive_sql='SELECT * FROM onedrive_service WHERE user_id=:id';
                    $get_onedrive_stmt=DB::getConnection()->prepare($get_onedrive_sql);
                    $get_onedrive_stmt->execute([
                        'id'=>$user_id
                    ]);
                    if($get_onedrive_stmt->rowCount()>0)
                    {
                        $result_array=$get_onedrive_stmt->fetch(PDO::FETCH_ASSOC);
                        $generated_at=date("Y-m-d H:i:s",strtotime($result_array['generated_at']));
                        $current_time=date("Y-m-d H:i:s",time());
                        $seconds_diff=strtotime($current_time)-strtotime($generated_at);
                        if($seconds_diff<$result_array['expires_in'])
                        {
                            return $result_array['access_token'];
                        }
                        else
                        {
                            $renewed_tokens=OneDriveService::renewTokens($result_array['refresh_token']);
                            $update_tokens_sql="UPDATE onedrive_service SET access_token=:access_token,refresh_token=:refresh_token,generated_at=:generated_at,expires_in=:expires_in";
                            $update_tokens_stmt=DB::getConnection()->prepare($update_tokens_sql);
                            $update_tokens_stmt->execute([
                                'access_token'=>$renewed_tokens['access_token'],
                                'refresh_token'=>$renewed_tokens['refresh_token'],
                                'generated_at'=>date("Y-m-d H:i:s"),
                                'expires_in'=>$renewed_tokens['expires_in']
                            ]);
                            return $renewed_tokens['access_token'];
                        }
                    }
                    else
                    {
                        return null;
                    }
                    break;
                }
                case 'googledrive':
                {
                    $get_gdrive_sql='SELECT * FROM googledrive_service WHERE user_id=:id';
                    $get_gdrive_stmt=DB::getConnection()->prepare($get_gdrive_sql);
                    $get_gdrive_stmt->execute([
                        'id'=>$user_id
                    ]);
                    if($get_gdrive_stmt->rowCount()>0)
                    {
                       
                        $result_array=$get_gdrive_stmt->fetch(PDO::FETCH_ASSOC);
                        $generated_at=date("Y-m-d H:i:s",strtotime($result_array['generated_at']));
                        $current_time=date("Y-m-d H:i:s",time());
                        $seconds_diff=strtotime($current_time)-strtotime($generated_at);
                        if($seconds_diff<$result_array['expires_in'])
                        {
                            return $result_array['access_token'];
                        }
                        else
                        {
                            $renewed_tokens=GoogleDriveService::renewAccessToken($result_array['refresh_token']);
                            $update_tokens_sql="UPDATE googledrive_service SET access_token=:access_token,generated_at=:generated_at,expires_in=:expires_in";
                            $update_tokens_stmt=DB::getConnection()->prepare($update_tokens_sql);
                            $update_tokens_stmt->execute([
                                'access_token'=>$renewed_tokens['access_token'],
                                'generated_at'=>date("Y-m-d H:i:s"),
                                'expires_in'=>$renewed_tokens['expires_in']
                            ]);
                            return $renewed_tokens['access_token'];
                        }
                    }
                    else
                    {
                        return null;
                    }
                    break;
                }
                case 'dropbox':
                {
                    $get_dropbox_sql='SELECT * FROM dropbox_service WHERE user_id=:id';
                    $get_dropbox_stmt=DB::getConnection()->prepare($get_dropbox_sql);
                    $get_dropbox_stmt->execute([
                        'id'=>$user_id
                    ]);
                    if($get_dropbox_stmt->rowCount()>0)
                    {
                        $result_array=$get_dropbox_stmt->fetch(PDO::FETCH_ASSOC);
                        return $result_array['access_token'];
                    }
                    else
                    {
                        return null;
                    }
                    break;
                }
            }
        }

		public function updateUsername($username,$id)
		{

			if($this->checkExistingUsername($username))
			{
				throw new UsernameTakenException();
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
				throw new IncorrectPasswordException();
			}
		}

		public function invalidateService($user_id, $service)
		{
			$delete_sql = '';
			switch ($service) {
				case 'onedrive':
					$delete_sql = "DELETE FROM onedrive_service WHERE user_id=:user_id";
					break;
				case 'googledrive':
					$delete_sql = "DELETE FROM googledrive_service WHERE user_id=:user_id";
					GoogleDriveService::removeAccessRefreshToken($this->getAccessToken($user_id,'googledrive'));
					break;
				case 'dropbox':
					$delete_sql="DELETE FROM dropbox_service WHERE user_id=:user_id";
					break;
			}
			$stmt = DB::getConnection()->prepare($delete_sql);
			$stmt->execute([
				'user_id' => $user_id
			]);
		}

		public function getUserStorageData($user_id)
		{
			$result["googledrive"]["available"] = $this->isGoogleDriveAuthorized($user_id);	
			$result["onedrive"]["available"] = $this->isOneDriveAuthorized($user_id);
			$result["dropbox"]["available"] = $this->isDropboxAuthorized($user_id);

			if($result["onedrive"]["available"]) {
				$result["onedrive"]["total"] = OnedriveService::getDriveQuota($this->getAccessToken($user_id, 'onedrive'))["total"];
				$result["onedrive"]["used"] = OnedriveService::getDriveQuota($this->getAccessToken($user_id, 'onedrive'))["used"];
			}
            if($result["googledrive"]["available"]) {
                $result["googledrive"]["total"] = GoogleDriveService::getStorageQuota($this->getAccessToken($user_id, 'googledrive'))["limit"];
                $result["googledrive"]["used"] = GoogleDriveService::getStorageQuota($this->getAccessToken($user_id, 'googledrive'))["usage"];
            }
			if($result["dropbox"]["available"]){
			    $result["dropbox"]["total"] = DropboxService::getStorageQuota($this->getAccessToken($user_id, 'dropbox'))["allocation"]["allocated"];
			    $result["dropbox"]["used"] = DropboxService::getStorageQuota($this->getAccessToken($user_id, 'dropbox'))["used"];
			}
			return $result;
		}
	}
	

?>