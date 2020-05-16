<?php

class MDownload
{
	public function testDownloadGoogledrive($user_id)
	{
		//GoogleDriveService::listAllFiles($this->getAccessToken($user_id,'googledrive'));
		//GoogleDriveService::downloadFileById($this->getAccessToken($user_id,'googledrive'), '1b30_XpO5J3XbGSLK6A_DorK6Tzuo7M0B');
		//GoogleDriveService::downloadFileById($this->getAccessToken($user_id,'googledrive'), '1b30_XpO5J3XbGSLK6A_DorK6Tzuo7M0B');
		//GoogleDriveService::downloadFileById($this->getAccessToken($user_id,'googledrive'), '1jBeVdo4YYPoxrNOVYp3PoCy3NSlQyoiQ');
		//GoogleDriveService::downloadFileById($this->getAccessToken($user_id,'googledrive'), '1zY_LHlh643slxca6iJUdOqQwC0nApwUC');
	}

	public function testDownloadDropbox($user_id)
	{
		//DropboxService::listAllFiles($this->getAccessToken($user_id,'dropbox'));
		//DropboxService::downloadFileById($this->getAccessToken($user_id,'dropbox'), 'id:EYf7PryE5EAAAAAAAAAAQw');
		//DropboxService::downloadFileById($this->getAccessToken($user_id,'dropbox'), 'id:EYf7PryE5EAAAAAAAAAARA');
		//DropboxService::downloadFileById($this->getAccessToken($user_id,'dropbox'), 'id:EYf7PryE5EAAAAAAAAAARQ');
	}

	// preluata din mupload
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
}

?>