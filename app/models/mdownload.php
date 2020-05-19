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

        $check_download_id = "SELECT * FROM DOWNLOADS WHERE download_id = :download_id";
        $check_download_stmt = DB::getConnection()->prepare($check_download_id);
        $check_download_stmt->execute([
            'download_id'=>$download_id
        ]);
        
        if($check_download_stmt->rowCount() == 0)
        {
            throw new InvalidDownloadId();
        }


        $file_id = $check_download_stmt->fetch(PDO::FETCH_ASSOC)["file_id"];

        //=====
            // verificare daca fisierul este fragmentat -> logica pentru identificare fragmente, descarcare fragmente de pe servicii si compunere
            // daca fisierul este redundant, se alege un serviciu pe care e disponibil, se descarca
            // in ambele cazuri, variabila $path trebuie initializata cu calea catre fisier rezultat din .../downloads
        //===== 

        // partea care trimite fisierul catre cel care il cere

        $path = 'D:\folderTesteDownload\Fisier1MB.txt';
        $chunk_size = 1024 * 1024 * 8; // unitati de cate 8MB
        $fd = fopen($path, "rb");
        if ($fd)
        {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.basename($path).'"');
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

        //====
            // dupa descarcare, probabil fisierul ar trebui sters, la fel si intrarea din tabela downloads pentru ca link-ul sa nu mai fie valid
        //====
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