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

        // teste pentru delete
        //GoogleDriveService::deleteFileById($this->getAccessToken($user_id,'googledrive'), '1zib1Wej5guQG1oz0RCVMlKZcJFof41a7');
        //DropboxService::deleteFileById($this->getAccessToken($user_id,'dropbox'), 'id:EYf7PryE5EAAAAAAAAAASQ');
	}

    public function createDownload($user_id, $file_id, $download_id)
    {
        $check_file_id_sql = "SELECT * FROM FILES WHERE item_id = :file_id";
        $check_file_id_stmt = DB::getConnection()->prepare($check_file_id_sql);
        $check_file_id_stmt->execute([
            'file_id'=>$file_id
        ]);
        if($check_file_id_stmt->rowCount() == 0) {
            throw new InvalidItemId();
        }

        // obtinere servicii necesare si verificare autorizare + existenta fragment
        $get_required_services_sql = "SELECT service, service_id FROM FILES f JOIN FRAGMENTS fr ON f.fragments_id = fr.fragments_id WHERE item_id=:file_id";
        $get_required_services_stmt = DB::getConnection()->prepare($get_required_services_sql);
        $get_required_services_stmt->execute([
            'file_id'=>$file_id
        ]);

        if($get_required_services_stmt->rowCount() > 0)
        {
            $available_services = $this->getAvailableServices($user_id);
            while($row = $get_required_services_stmt->fetch(PDO::FETCH_ASSOC))
            {
                switch($row['service'])
                {
                    case 'onedrive':
                        if($available_services['onedrive'] == false) {
                            throw new MissingOneDriveAuthException();
                        }
                        OnedriveService::getFileMetadataById($this->getAccessToken($user_id,'onedrive'), $row['service_id']);
                        break;
                    case 'dropbox':
                        if($available_services['dropbox'] == false) {
                            throw new MissingDropboxAuthException();
                        }
                        DropboxService::getFileMetadataById($this->getAccessToken($user_id,'dropbox'), $row['service_id']);
                        break;
                    case 'googledrive':
                        if($available_services['googledrive'] == false) {
                            throw new MissingGoogledriveAuthException();
                        }
                        GoogleDriveService::getFileMetadataById($this->getAccessToken($user_id,'googledrive'), $row['service_id']);
                        break;
                }
            }
        }

        // la final, daca totul merge bine
        $insert_download_sql = "INSERT INTO DOWNLOADS(user_id, file_id, download_id) VALUES (:user_id, :file_id, :download_id)";
        $insert_download_stmt = DB::getConnection()->prepare($insert_download_sql);
        $insert_download_stmt->execute([
            'user_id' => $user_id,
            'file_id' => $file_id,
            'download_id' => $download_id
        ]);
    }

    public function downloadFile($download_id)
    {

        // verificare download si obtinere date utile
        $check_download_id_sql = "SELECT user_id, file_id, name, fragments_id FROM FILES f JOIN DOWNLOADS ON file_id = item_id WHERE download_id = :download_id";
        $check_download_stmt = DB::getConnection()->prepare($check_download_id_sql);
        $check_download_stmt->execute([
            'download_id'=>$download_id
        ]);
        
        if($check_download_stmt->rowCount() == 0)
        {
            throw new InvalidDownloadId();
        }

        $download_info = $check_download_stmt->fetch(PDO::FETCH_ASSOC);
        $user_id = $download_info['user_id'];
        $file_id = $download_info['file_id'];
        $file_name = $download_info['name'];
        $fragments_id = $download_info['fragments_id'];

        // !!! Nu uita, pentru redundant trebuie o abordare diferita ...

        // caut fragmentele si le descarc intr-un singur fisier
        $get_storage_services_sql = "SELECT service, service_id FROM FRAGMENTS WHERE fragments_id = :fragments_id";
        $get_storage_services_stmt = DB::getConnection()->prepare($get_storage_services_sql);
        $get_storage_services_stmt->execute([
            'fragments_id'=>$fragments_id
        ]);

        $path = null;
        if($get_storage_services_stmt->rowCount() > 0)
        {
            // fisier temporar in care se vor face toate append-urile
            $temp_file_name = uniqid("", true);
            $path = $_SERVER['DOCUMENT_ROOT'].'/ProiectTW/downloads/'. $temp_file_name;

            while($row = $get_storage_services_stmt->fetch(PDO::FETCH_ASSOC))
            {
                switch($row['service'])
                {
                    case 'onedrive':
                        OnedriveService::downloadFileById($this->getAccessToken($user_id,'onedrive'), $row['service_id'], $path);
                        break;
                    case 'dropbox':
                        DropboxService::downloadFileById($this->getAccessToken($user_id,'dropbox'), $row['service_id'], $path);
                        break;
                    case 'googledrive':
                        GoogleDriveService::downloadFileById($this->getAccessToken($user_id,'googledrive'), $row['service_id'], $path);
                        break;
                }
            }
        }

        $chunk_size = 1024 * 1024 * 8; // unitati de cate 8MB
        $fd = fopen($path, "rb");
        if ($fd)
        {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . $file_name . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($path));

            while(!feof($fd)) {
                $buffer = fread($fd, $chunk_size);
                echo $buffer;
                ob_flush();
                flush();
            }
        }
        else {
            echo "Error opening file";
        }
        fclose($fd);

        // stergere fisier si "invalidare" link download
        unlink($path);
        $delete_download_sql = "DELETE FROM DOWNLOADS WHERE download_id = :download_id";
        $delete_download_stmt = DB::getConnection()->prepare($delete_download_sql);
        $delete_download_stmt->execute([
            'download_id'=>$download_id
        ]);

    }

    public function getAvailableServices($user_id)
    {
        $result = array();

        $get_onedrive_query = "SELECT user_id FROM onedrive_service WHERE user_id = :user_id";
        $get_onedrive_stmt = DB::getConnection()->prepare($get_onedrive_query);
        $get_onedrive_stmt->execute(['user_id'=>$user_id]);
        if($get_onedrive_stmt->rowCount() > 0)
            $result["onedrive"] = true;
        else
            $result["onedrive"] = false;

        $get_googledrive_query = "SELECT user_id FROM googledrive_service WHERE user_id = :user_id";
        $get_googledrive_stmt = DB::getConnection()->prepare($get_googledrive_query);
        $get_googledrive_stmt->execute(['user_id'=>$user_id]);
        if($get_googledrive_stmt->rowCount()>0)
            $result["googledrive"] = true;
        else
            $result["googledrive"] = false;

        $get_dropbox_query = "SELECT user_id FROM dropbox_service WHERE user_id = :user_id";
        $get_dropbox_stmt = DB::getConnection()->prepare($get_dropbox_query);
        $get_dropbox_stmt->execute(['user_id'=>$user_id]);
        if($get_dropbox_stmt->rowCount()>0)
            $result["dropbox"] = true;
        else
            $result["dropbox"] = false;

        return $result;
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