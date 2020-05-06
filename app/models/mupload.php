<?php
    class MUpload
    {
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
                    $insert_upload_sql='INSERT INTO UPLOADS(user_id,upload_id,parent_id,file_reference,name,expected_size) VALUES (:user_id,:upload_id,:parent_id,:file_reference,:name,:expected_size)';
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
    public function uploadFile($upload_id,$chunk_size)
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
                file_put_contents($path,$post_data);
                if(filesize($path)==$upload_array['expected_size'])
                {
                    return 1;
                }
                else return 0;    
            }
        }
        else
        {
            throw new InvalidUploadId();
        }
    }
}
?>