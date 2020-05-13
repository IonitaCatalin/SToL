<?php
    class MUpload
    {
        public function testUploadOnedrive($user_id)
        {
            $path=$_SERVER['DOCUMENT_ROOT'].'/ProiectTW/uploads/17181a8a28e685844800cfc9737122ab';
            OneDriveService::uploadFile($this->getAccessToken($user_id,'onedrive'),$path,0,20000000 );

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
                        var_dump($result_array);
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
        public function startUpload($upload_id,$user_id,$parent_id,$filename,$filesize)
        {
            $check_parent_sql='SELECT item_id FROM FOLDERS WHERE item_id=:id';
            $check_parent_stmt=DB::getConnection()->prepare($check_parent_sql);
            $check_parent_stmt->execute([
                'id'=>$parent_id
            ]);
            if($check_parent_stmt->rowCount()>0)
            {   
                $check_name_sql='SELECT item_id FROM FILES WHERE folder_id=:id AND name=:name';
                $check_name_stmt=DB::getConnection()->prepare($check_name_sql);
                $check_name_stmt->execute([
                    'id'=>$parent_id,
                    'name'=>$filename
                ]);
                if($check_name_stmt->rowCount()==0)
                {
                    $bytes=random_bytes(16);
                    $file_reference=bin2hex($bytes);
                    $insert_upload_sql="INSERT INTO UPLOADS(user_id,upload_id,parent_id,file_reference,name,expected_size,status) VALUES (:user_id,:upload_id,:parent_id,:file_reference,:name,:expected_size,'chunking')";
                    $insert_upload_stmt=DB::getConnection()->prepare($insert_upload_sql);
                    $insert_upload_stmt->execute([
                        'user_id'=>$user_id,
                        'upload_id'=>$upload_id,
                        'parent_id'=>$parent_id,
                        'file_reference'=>$file_reference,
                        'name'=>$filename,
                        'expected_size'=>$filesize
                    ]);
                    $path = $_SERVER['DOCUMENT_ROOT'].'/ProiectTW/uploads/'.$file_reference;
                    $file = fopen($path,"w");
                    fclose($file);
                }
                else
                {
                    throw new ItemnameTaken();
                }
            }
            else
            {
                throw new InvalidItemParentId();
            }
        }
    public function appendChunks($upload_id,$chunk_size)
    {
        $check_upload_id="SELECT * FROM UPLOADS WHERE upload_id=:id";
        $check_upload_stmt=DB::getConnection()->prepare($check_upload_id);
        $check_upload_stmt->execute([
            'id'=>$upload_id
        ]);
        if($check_upload_stmt->rowCount()>0)
        {
            $post_data=file_get_contents('php://input');
            if(strlen($post_data)>$chunk_size)
            {
                throw new UnsupportedChunkSize();
            }
            else
            {   
                $upload_array=$check_upload_stmt->fetch(PDO::FETCH_ASSOC);
                $path=$_SERVER['DOCUMENT_ROOT'].'/ProiectTW/uploads/'.$upload_array['file_reference'];
                file_put_contents($path,$post_data,FILE_APPEND);
                if(filesize($path)==$upload_array['expected_size'])
                {
                    return true;
                }
                else return false;    
            }
        }
        else
        {
            throw new InvalidUploadId();
        }
    }
    public function deletePublicUpload($upload_id)
    {
        $check_upload_sql="SELECT * FROM UPLOADS WHERE upload_id=:id";
        $check_upload_stmt=DB::getConnection()->prepare($check_upload_sql);
        $check_upload_stmt->execute([
            'id'=>$upload_id
        ]);
        if($check_upload_stmt->rowCount()>0)
        {
            
            $result_upload=$check_upload_stmt->fetch(PDO::FETCH_ASSOC);
            if($result_upload['status']=='chunking')
            {
                unlink($_SERVER['DOCUMENT_ROOT'].'/ProiectTW/uploads/'.$result_upload['file_reference']);
                $delete_upload_sql="DELETE FROM uploads WHERE upload_id=:id";
                $delete_upload_stmt=DB::getConnection()->prepare($delete_upload_sql);
                $delete_upload_stmt->execute([
                    'id'=>$upload_id
                ]);
            }
            else
            {
                throw new DeleteSplittingFile();
            }
        }   
        else
        {
            throw new InvalidUploadId();
        }
    }
    public function statusChangeToSplitting($upload_id)
    {
        $change_status_sql="UPDATE uploads SET status='splitting' WHERE upload_id=:id";
        $change_status_stmt=DB::getConnection()->prepare($change_status_sql);
        $change_status_stmt->execute([
            'id'=>$upload_id
        ]);
    }

}
?>