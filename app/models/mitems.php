<?php
    class MItems
    {
        public function createFolderToParent($user_id,$parent_id,$folder_name)
        {
            $search_parent_sql="SELECT item_id FROM ITEMS WHERE item_id=:id AND content_type='folder'";
            $search_parent_stmt=DB::getConnection()->prepare($search_parent_sql);
            $search_parent_stmt->execute([
                'id'=>$parent_id
            ]);
            $search_parent_result=$search_parent_stmt->fetch(PDO::FETCH_ASSOC);

            if($search_parent_stmt->rowCount()>0)
            {
                
                $check_name_sql="SELECT item_id FROM FOLDERS WHERE parent_id=:id AND name=:name";
                $check_name_stmt=DB::getConnection()->prepare($check_name_sql);
                $check_name_stmt->execute([
                    'id'=>$parent_id,
                    'name'=>$folder_name
                ]);
                if($check_name_stmt->rowCount()==0)
                {
                    $bytes=random_bytes(16);
                    $folder_item_id=bin2hex($bytes);
                    $insert_item_sql="INSERT INTO ITEMS (user_id,item_id,content_type) VALUES(:user_id,:item_id,'folder')";
                    $insert_item_stmt=DB::getConnection()->prepare($insert_item_sql);
                    $insert_item_stmt->execute([
                        'user_id'=>$user_id,
                        'item_id'=>$folder_item_id
                    ]);
                    
                    $insert_folder_sql="INSERT INTO FOLDERS(item_id,parent_id,name,created_at) VALUES(:item_id,:parent_id,:name,:created_at)";
                    $insert_folder_stmt=DB::getConnection()->prepare($insert_folder_sql);
                    $insert_folder_stmt->execute([
                        'item_id'=>$folder_item_id,
                        'parent_id'=>$parent_id,
                        'name'=>$folder_name,
                        'created_at'=>date('Y-m-d H:i:s')
                    ]);
                }
                else
                {
                    throw new ItemNameTaken();
                }
            }
            else
            {
                throw new InvalidItemId();
            }
        }

        public function createFolderToRoot($user_id,$folder_name)
        {
                $get_root_sql="SELECT fold.item_id FROM ITEMS itms JOIN FOLDERS fold ON fold.item_id=itms.item_id WHERE user_id=:id AND parent_id IS NULL";
                $get_root_stmt=DB::getConnection()->prepare($get_root_sql);
                $get_root_stmt->execute([
                    'id'=>$user_id,
                ]);
                $result_list=$get_root_stmt->fetch(PDO::FETCH_ASSOC);
                $root_id=$result_list['item_id'];
                $check_name_sql="SELECT item_id FROM FOLDERS WHERE parent_id=:id AND name=:name";
                $check_name_stmt=DB::getConnection()->prepare($check_name_sql);
                $check_name_stmt->execute([
                    'id'=>$root_id,
                    'name'=>$folder_name
                ]);
                if($check_name_stmt->rowCount()==0)
                {
                    $bytes=random_bytes(16);
                    $folder_item_id=bin2hex($bytes);
                    $insert_item_sql="INSERT INTO ITEMS (user_id,item_id,content_type) VALUES(:user_id,:item_id,'folder')";
                    $insert_item_stmt=DB::getConnection()->prepare($insert_item_sql);
                    $insert_item_stmt->execute([
                        'user_id'=>$user_id,
                        'item_id'=>$folder_item_id
                    ]);
                    
                    $insert_folder_sql="INSERT INTO FOLDERS(item_id,parent_id,name,created_at) VALUES(:item_id,:parent_id,:name,:created_at)";
                    $insert_folder_stmt=DB::getConnection()->prepare($insert_folder_sql);
                    $insert_folder_stmt->execute([
                        'item_id'=>$folder_item_id,
                        'parent_id'=>$root_id,
                        'name'=>$folder_name,
                        'created_at'=>date('Y-m-d H:i:s')
                    ]);
                }
                else
                {
                    throw new ItemNameTaken();
                }
        }

        public function updateItemName($user_id,$item_id,$new_name)
        {
            $check_item_existence_sql="SELECT item_id,content_type FROM ITEMS WHERE item_id=:item AND user_id=:user";
            $check_item_existence_stmt=DB::getConnection()->prepare($check_item_existence_sql);
            $check_item_existence_stmt->execute([
                'item'=>$item_id,
                'user'=>$user_id
            ]);
            $result_list=$check_item_existence_stmt->fetch(PDO::FETCH_ASSOC);
            if($check_item_existence_stmt->rowCount()>0)
            {
                if($result_list['content_type']=='folder')
                {
                    $check_if_root_sql="SELECT item_id FROM FOLDERS WHERE item_id=:item AND parent_id IS NULL";
                    $check_if_root_stmt=DB::getConnection()->prepare($check_if_root_sql);
                    $check_if_root_stmt->execute([
                        'item'=>$item_id
                    ]);
                    if($check_if_root_stmt->rowCount()==0)
                    {
                        $get_parent_id="SELECT * FROM FOLDERS WHERE item_id=:item";
                        $get_parent_stmt=DB::getConnection()->prepare($get_parent_id);
                        $get_parent_stmt->execute([
                            'item'=>$item_id
                        ]);
                        $parent_id=$get_parent_stmt->fetch(PDO::FETCH_ASSOC)['parent_id'];
                        $check_name_sql="SELECT item_id FROM FOLDERS WHERE item_id!=:item AND parent_id=:parent AND name=:new_name";
                        $check_name_stmt=DB::getConnection()->prepare($check_name_sql);
                        $check_name_stmt->execute([
                            'item'=>$item_id,
                            'new_name'=>$new_name,
                            'parent'=>$parent_id
                        ]);
                        if($check_name_stmt->rowCount()==0)
                        {
                            $update_name_sql="UPDATE FOLDERS SET name=:new_name WHERE item_id=:item";
                            $update_name_stmt=DB::getConnection()->prepare($update_name_sql);
                            $update_name_stmt->execute([
                                'new_name'=>$new_name,
                                'item'=>$item_id,
                            ]);
                        }
                    
                        else
                        {
                            throw new ItemNameTaken();
                        }
                    }
                    else
                    {
                        throw new InvalidItemId();
                    }
                }
                else if($result_list['content_type']=='file')
                {
                    $get_parent_id="SELECT * FROM FILES WHERE item_id=:item";
                    $get_parent_stmt=DB::getConnection()->prepare($get_parent_id);
                    $get_parent_stmt->execute([
                        'item'=>$item_id
                    ]);
                    $parent_id=$get_parent_stmt->fetch(PDO::FETCH_ASSOC)['folder_id'];
                    $check_name_sql="SELECT item_id FROM FILES WHERE item_id!=:item_id AND folder_id=:parent AND name=:new_name";
                    $check_name_stmt=DB::getConnection()->prepare($check_name_sql);
                    $check_name_stmt->execute([
                        'item_id'=>$item_id,
                        'new_name'=>$new_name,
                        'parent'=>$parent_id
                    ]);
                    if($check_name_stmt->rowCount()==0)
                    {
                        $update_name_sql="UPDATE FILES SET name=:new_name WHERE item_id=:item_id";
                        $update_name_stmt=DB::getConnection()->prepare($update_name_sql);
                        $update_name_stmt->execute([
                            'new_name'=>$new_name,
                            'item_id'=>$item_id
                        ]);
                    }
                    else
                    {
                        throw new ItemNameTaken();
                    }
                }
            }
            else
            {
                throw new InvalidItemId();
            }
        }
        
        public function getItemMetadata($user_id,$item_id)
        {
            $search_item_exists_sql = "SELECT * FROM ITEMS WHERE item_id=:id";
            $search_item_exists_stmt = DB::getConnection()->prepare($search_item_exists_sql);
            $search_item_exists_stmt->execute([
                'id' => $item_id
            ]);
            $search_item_exists_result = $search_item_exists_stmt->fetch(PDO::FETCH_ASSOC);
            if($search_item_exists_stmt->rowCount()>0)
            {
                if($search_item_exists_result['content_type']=='folder')
                {
                    return $this->getItemsListFromFolder($user_id,$item_id);
                }
                else if($search_item_exists_result['content_type']=='file')
                {
                    $metadata_array=array();
                    $get_file_metadata_sql="SELECT * FROM FILES JOIN FRAGMENTS ON FILES.FRAGMENTS_ID=FILES.FRAGMENTS_ID WHERE FILES.ITEM_ID=:item_id";
                    $get_file_metadata_stmt=DB::getConnection()->prepare($get_file_metadata_sql);
                    $get_file_metadata_stmt->execute([
                        'item_id'=>$item_id
                    ]);
                    $result_rows=$get_file_metadata_stmt->fetchAll();
                    $metadata_array['filename']=$result_rows[0]['name'];
                    $filesize=0;
                    for($index=0;$index<count($result_rows);$index++)
                    {
                        $filesize+=$result_rows[$index]['fragment_size'];
                    }
                    $metadata_array['parent_id']=$result_rows[0]['folder_id'];
                    $metadata_array['filesize']=$filesize;
                    return $metadata_array;

                }
            }
            else
            {
                throw new InvalidItemId();
            }
        }
        public function getItemsListFromFolder($user_id, $parent_id)
        {

            $search_folder_exists_sql = "SELECT item_id FROM ITEMS WHERE item_id=:id AND content_type='folder'";
            $search_folder_exists_stmt = DB::getConnection()->prepare($search_folder_exists_sql);
            $search_folder_exists_stmt->execute([
                'id' => $parent_id
            ]);
            $search_folder_exists_result = $search_folder_exists_stmt->fetch(PDO::FETCH_ASSOC);

            if($search_folder_exists_stmt->rowCount() > 0)
            {

                $result_array = array();
                $count = 0;

                $list_folders_sql = "SELECT flds.item_id, flds.name, itms.content_type, itms.favorited FROM ITEMS itms JOIN FOLDERS flds ON itms.item_id = flds.item_id WHERE user_id=:user_id AND parent_id=:parent_id";
                $list_folders_stmt = DB::getConnection()->prepare($list_folders_sql);
                $list_folders_stmt->execute([
                    'user_id' => $user_id,
                    'parent_id' => $parent_id
                ]);
                if($list_folders_stmt->rowCount()>0)
                {
                    while($row = $list_folders_stmt->fetch(PDO::FETCH_ASSOC)) {
                        $result_array[$count] = $row;
                        $count ++ ;
                    }
                }
  
                $list_files_sql = "SELECT fls.item_id, fls.name, itms.content_type, itms.favorited FROM ITEMS itms JOIN FILES fls ON itms.item_id = fls.item_id WHERE user_id=:user_id AND folder_id=:parent_id";
                $list_files_stmt = DB::getConnection()->prepare($list_files_sql);
                $list_files_stmt->execute([
                    'user_id' => $user_id,
                    'parent_id' => $parent_id
                ]);
                if($list_files_stmt->rowCount()>0)
                {
                    while($row = $list_files_stmt->fetch(PDO::FETCH_ASSOC)) {
                        $result_array[$count] = $row;
                        $count ++ ;
                    }
                }

                return $result_array;
            }
            else
            {
                throw new InvalidItemId();
            }

        }

        public function getItemsListFromRoot($user_id)
        {
            $get_root_sql = "SELECT flds.item_id, flds.name, itms.content_type, itms.favorited FROM ITEMS itms JOIN FOLDERS flds ON itms.item_id = flds.item_id WHERE user_id=:user_id AND parent_id IS NULL";
            $get_root_stmt = DB::getConnection()->prepare($get_root_sql);
            $get_root_stmt->execute([
                'user_id' => $user_id,
            ]);

            $root_row = $get_root_stmt->fetch(PDO::FETCH_ASSOC);
            $root_id = $root_row['item_id'];
            $result_array = array();
            $result_array[0] = $root_row;   //pun si root id in datele trimise, e util
            $count = 1;

            $list_folders_sql = "SELECT flds.item_id, flds.name, itms.content_type, itms.favorited FROM ITEMS itms JOIN FOLDERS flds ON itms.item_id = flds.item_id WHERE user_id=:user_id AND parent_id=:parent_id";
            $list_folders_stmt = DB::getConnection()->prepare($list_folders_sql);
            $list_folders_stmt->execute([
                'user_id' => $user_id,
                'parent_id' => $root_id
            ]);
            if($list_folders_stmt->rowCount()>0)
            {
                while($row = $list_folders_stmt->fetch(PDO::FETCH_ASSOC)) {
                    $result_array[$count] = $row;
                    $count ++ ;
                }
            }

            $list_files_sql = "SELECT fls.item_id, fls.name, itms.content_type, itms.favorited FROM ITEMS itms JOIN FILES fls ON itms.item_id = fls.item_id WHERE user_id=:user_id AND folder_id=:parent_id";
            $list_files_stmt = DB::getConnection()->prepare($list_files_sql);
            $list_files_stmt->execute([
                'user_id' => $user_id,
                'parent_id' => $root_id
            ]);
            if($list_files_stmt->rowCount()>0)
            {
                while($row = $list_files_stmt->fetch(PDO::FETCH_ASSOC)) {
                    $result_array[$count] = $row;
                    $count ++ ;
                }
            }

            return $result_array;
        }

        public function deleteItem($user_id, $item_id)
        {
            $search_folder_exists_sql = "SELECT item_id FROM ITEMS WHERE user_id=:user_id AND item_id=:item_id AND content_type='folder'";
            $search_folder_exists_stmt = DB::getConnection()->prepare($search_folder_exists_sql);
            $search_folder_exists_stmt->execute([
                'user_id' => $user_id,
                'item_id' => $item_id
            ]);
            $search_folder_exists_result = $search_folder_exists_stmt->fetch(PDO::FETCH_ASSOC);

            if($search_folder_exists_stmt->rowCount() > 0)
            {
                $this->deleteFolder($user_id, $item_id);
                return; // !!!
            }

            $search_file_exists_sql = "SELECT item_id FROM ITEMS WHERE user_id=:user_id AND item_id=:item_id AND content_type='file'";
            $search_file_exists_stmt = DB::getConnection()->prepare($search_file_exists_sql);
            $search_file_exists_stmt->execute([
                'user_id' => $user_id,
                'item_id' => $item_id
            ]);
            $search_file_exists_result = $search_file_exists_stmt->fetch(PDO::FETCH_ASSOC);

            if($search_file_exists_stmt->rowCount() > 0)
            {
                $this->deleteFile($user_id,$item_id);
                return; // !!!
            }

            throw new InvalidItemId();

        }

        public function deleteFile($user_id,$item_id)
        {
            $get_file_fragments_sql="SELECT fragments_id FROM FILES WHERE item_id=:item_id";
            $get_file_fragments_stmt=DB::getConnection()->prepare($get_file_fragments_sql);
            $get_file_fragments_stmt->execute([
                'item_id'=>$item_id
            ]);
            $result_fragment_id=$get_file_fragments_stmt->fetch(PDO::FETCH_ASSOC);

            $get_each_fragment_sql="SELECT * FROM FRAGMENTS WHERE fragments_id=:fragments_id";
            $get_each_fragment_stmt=DB::getConnection()->prepare($get_each_fragment_sql);
            $get_each_fragment_stmt->execute([
                'fragments_id'=>$result_fragment_id['fragments_id']
            ]);
            $result_fragments=$get_each_fragment_stmt->fetchAll();

            for($index=0;$index<count($result_fragments);$index++)
            {
                if($result_fragments[$index]['service']=='onedrive')
                {
                    OneDriveService::deleteFileById($this->getAccessToken($user_id,'onedrive'),$result_fragments[$index]['service_id']);
                }
                else if($result_fragments[$index]['service']=='googledrive')
                {
                    GoogleDriveService::deleteFileById($this->getAccessToken($user_id,'googledrive'),$result_fragments[$index]['service_id']);
                }
                else if($result_fragments[$index]['service']=='dropbox')
                {
                    DropboxService::deleteFileById($this->getAccessToken($user_id,'dropbox'),$result_fragments[$index]['service_id']);
                }
            }
            $delete_fragments_sql="DELETE FROM FRAGMENTS WHERE fragments_id=:fragments_id";
            $delete_fragments_stmt=DB::getConnection()->prepare($delete_fragments_sql);
            $delete_fragments_stmt->execute([
                'fragments_id'=>$result_fragment_id['fragments_id']
            ]);
            

            $delete_file_sql = "DELETE FROM files WHERE item_id=:item_id";
            $delete_file_stmt = DB::getConnection()->prepare($delete_file_sql);
            $delete_file_stmt->execute(['item_id' => $item_id]);
            //echo "Deleted $item_id from files.";

            $delete_item_sql = "DELETE FROM items WHERE item_id=:item_id";
            $delete_item_stmt = DB::getConnection()->prepare($delete_item_sql);
            $delete_item_stmt->execute(['item_id' => $item_id]);
            
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

        public function deleteFolder($user_id, $item_id)
        {
            $files_in_folder = $this->getItemsListFromFolder($user_id, $item_id);
            //print_r($files_in_folder);
            foreach($files_in_folder as $key => $file){ //key e index-ul 0, 1, 2...
                if($file['content_type'] == 'file'){
                    $this->deleteFile($user_id,$file['item_id']);
                }
                else if($file['content_type'] == 'folder'){
                    $this->deleteFolder($user_id, $file['item_id']);
                }
            }

            $delete_folder_sql = "DELETE FROM folders WHERE item_id=:item_id";
            $delete_folder_stmt = DB::getConnection()->prepare($delete_folder_sql);
            $delete_folder_stmt->execute(['item_id' => $item_id]);
            //echo "Deleted $item_id from folders.";

            $delete_item_sql = "DELETE FROM items WHERE item_id=:item_id";
            $delete_item_stmt = DB::getConnection()->prepare($delete_item_sql);
            $delete_item_stmt->execute(['item_id' => $item_id]);
            //echo "Deleted $item_id from items.";
        }

        public function moveItem($user_id, $item_id, $new_parent_id)
        {
            // verific existenta item-ului
            $search_file_exists_sql = "SELECT item_id FROM ITEMS WHERE user_id=:user_id AND item_id=:item_id";
            $search_file_exists_stmt = DB::getConnection()->prepare($search_file_exists_sql);
            $search_file_exists_stmt->execute([
                'user_id' => $user_id,
                'item_id' => $item_id
            ]);
            $search_file_exists_result = $search_file_exists_stmt->fetch(PDO::FETCH_ASSOC);

            if($search_file_exists_stmt->rowCount() == 0) {
                throw new InvalidItemId();
            }

            // verific existenta parent-ului
            $search_new_parent_exists_sql = "SELECT item_id FROM ITEMS WHERE user_id=:user_id AND item_id=:item_id AND content_type='folder'";
            $search_new_parent_exists_stmt = DB::getConnection()->prepare($search_new_parent_exists_sql);
            $search_new_parent_exists_stmt->execute([
                'user_id' => $user_id,
                'item_id' => $new_parent_id
            ]);
            $search_new_parent_exists_result = $search_new_parent_exists_stmt->fetch(PDO::FETCH_ASSOC);

            if($search_new_parent_exists_stmt->rowCount() == 0) {
                throw new InvalidItemParentId();
            }

            // verific daca in noul parent exista un folder cu acelasi nume
            $file_data = $this->getDataAboutFile($user_id, $item_id);
            //print_r($file_data);
            $files_in_new_parent = $this->getItemsListFromFolder($user_id, $new_parent_id);
            //print_r($files_in_new_parent);
            foreach($files_in_new_parent as $key => $new_parent_file){ //key e index-ul 0, 1, 2...
                if($new_parent_file['name'] == $file_data['name'] && $new_parent_file['content_type'] == $file_data['content_type']){
                    throw new MoveInvalidNameAndType();
                }
            }

            // schimb parent-ul fisierului sau folderului curent
            if($file_data["content_type"] == "file") {
                $change_parent_sql = "UPDATE files SET folder_id=:new_parent_id WHERE item_id=:item_id";
                $change_parent_stmt = DB::getConnection()->prepare($change_parent_sql);
                $change_parent_stmt->execute([
                    'new_parent_id' => $new_parent_id,
                    'item_id' => $item_id
                ]);
            }
            else if($file_data["content_type"] == "folder") {
                $change_parent_sql = "UPDATE folders SET parent_id=:new_parent_id WHERE item_id=:item_id";
                $change_parent_stmt = DB::getConnection()->prepare($change_parent_sql);
                $change_parent_stmt->execute([
                    'new_parent_id' => $new_parent_id,
                    'item_id' => $item_id
                ]);
            }
        }

        public function getDataAboutFile($user_id, $item_id)
        {
            // date despre folder
            $check_folder_sql = "SELECT flds.item_id, flds.name, itms.content_type FROM ITEMS itms JOIN FOLDERS flds ON itms.item_id = flds.item_id WHERE user_id=:user_id AND flds.item_id=:item_id";
            $check_folder_stmt = DB::getConnection()->prepare($check_folder_sql);
            $check_folder_stmt->execute([
                'user_id' => $user_id,
                'item_id' => $item_id
            ]);
            if($check_folder_stmt->rowCount()>0)
            {
                return $check_folder_stmt->fetch(PDO::FETCH_ASSOC);
            }

            // sau date despre fisier
            $check_item_sql = "SELECT fls.item_id, fls.name, itms.content_type FROM ITEMS itms JOIN FILES fls ON itms.item_id = fls.item_id WHERE user_id=:user_id AND fls.item_id=:item_id";
            $check_item_stmt = DB::getConnection()->prepare($check_item_sql);
            $check_item_stmt->execute([
                'user_id' => $user_id,
                'item_id' => $item_id
            ]);
            if($check_item_stmt->rowCount()>0)
            {
                return $check_item_stmt->fetch(PDO::FETCH_ASSOC);
            }
        }
        public function searchByNameFromRoot($user_id,$search_name)
        {
        
            $get_user_root_sql="SELECT FOLDERS.item_id FROM ITEMS JOIN FOLDERS ON ITEMS.ITEM_ID=FOLDERS.ITEM_ID WHERE FOLDERS.PARENT_ID IS NULL AND ITEMS.USER_ID=:user_id AND ITEMS.CONTENT_TYPE='folder'";
            $get_user_root_stmt=DB::getConnection()->prepare($get_user_root_sql);
            $get_user_root_stmt->execute([
                'user_id'=>$user_id
            ]);
            $result_root=$get_user_root_stmt->fetch(PDO::FETCH_ASSOC);
            $result_items=array();
            $search_in=array();
            //$search_in[0]=$result_root['item_id'];
            array_push($search_in,$result_root['item_id']);
            
            for($iterator=0;$iterator<count($search_in);$iterator++)
            {
                
                $get_folders_children_sql="SELECT * FROM FOLDERS WHERE parent_id=:parent_id";
                $get_folders_children_stmt=DB::getConnection()->prepare($get_folders_children_sql);
                $get_folders_children_stmt->execute([
                    'parent_id'=>$search_in[$iterator]
                ]);
                $result_folders=$get_folders_children_stmt->fetchAll();
                for($folders_iterator=0;$folders_iterator<count($result_folders);$folders_iterator++)
                {
                      $search_in[count($search_in)]=$result_folders[$folders_iterator]['item_id'];
                      if(strpos($result_folders[$folders_iterator]['name'],$search_name)!==false)
                      {
                            array_push($result_items,['item_id'=>$result_folders[$folders_iterator]['item_id'],'parent_id'=>$result_folders[$folders_iterator]['parent_id'],'name'=>$result_folders[$folders_iterator]['name'],'content_type'=>'folder']);
                      }
                }
                $get_files_children_sql="SELECT * FROM FILES WHERE FOLDER_ID=:folder_id";
                $get_files_children_stmt=DB::getConnection()->prepare($get_files_children_sql);
                $get_files_children_stmt->execute([
                    'folder_id'=>$search_in[$iterator]
                ]);
                $result_files=$get_files_children_stmt->fetchAll();
                for($files_iterator=0;$files_iterator<count($result_files);$files_iterator++)
                {
                      if(strpos($result_files[$files_iterator]['name'],$search_name)!==false)
                      {
                            array_push($result_items,['item_id'=>$result_files[$files_iterator]['item_id'],'parent_id'=>$search_in[$iterator],'name'=>$result_files[$files_iterator]['name'],'content_type'=>'file']);
                      }
                }
            }
            return $result_items;
            
        }

        public function getFavoritedItems($user_id)
        {
            $result_array = array();
            $count = 0;

            $list_folders_sql = "SELECT flds.item_id, flds.name, itms.content_type, itms.favorited FROM ITEMS itms JOIN FOLDERS flds ON itms.item_id = flds.item_id WHERE user_id=:user_id AND favorited=TRUE";
            $list_folders_stmt = DB::getConnection()->prepare($list_folders_sql);
            $list_folders_stmt->execute([
                'user_id' => $user_id
            ]);
            if($list_folders_stmt->rowCount()>0)
            {
                while($row = $list_folders_stmt->fetch(PDO::FETCH_ASSOC)) {
                    $result_array[$count] = $row;
                    $count ++ ;
                }
            }

            $list_files_sql = "SELECT fls.item_id, fls.name, itms.content_type, itms.favorited FROM ITEMS itms JOIN FILES fls ON itms.item_id = fls.item_id WHERE user_id=:user_id AND favorited=TRUE";
            $list_files_stmt = DB::getConnection()->prepare($list_files_sql);
            $list_files_stmt->execute([
                'user_id' => $user_id
            ]);
            if($list_files_stmt->rowCount()>0)
            {
                while($row = $list_files_stmt->fetch(PDO::FETCH_ASSOC)) {
                    $result_array[$count] = $row;
                    $count ++ ;
                }
            }
            return $result_array;
        }


        public function addToFavorites($user_id, $item_id)
        {
            // verific existenta item-ului
            $search_item_exists_sql = "SELECT item_id FROM ITEMS WHERE user_id=:user_id AND item_id=:item_id";
            $search_item_exists_stmt = DB::getConnection()->prepare($search_item_exists_sql);
            $search_item_exists_stmt->execute([
                'user_id' => $user_id,
                'item_id' => $item_id
            ]);
            $search_item_exists_result = $search_item_exists_stmt->fetch(PDO::FETCH_ASSOC);

            if($search_item_exists_stmt->rowCount() == 0) {
                throw new InvalidItemId();
            }
            else
            {
                $update_fav_sql="UPDATE ITEMS SET favorited=TRUE WHERE user_id=:user_id AND item_id=:item_id";
                $update_fav_stmt=DB::getConnection()->prepare($update_fav_sql);
                $update_fav_stmt->execute([
                    'user_id' => $user_id,
                    'item_id'=>$item_id,
                ]);
            }
        }

        public function removeFromFavorites($user_id, $item_id)
        {
            // verific existenta item-ului
            $search_item_exists_sql = "SELECT item_id FROM ITEMS WHERE user_id=:user_id AND item_id=:item_id";
            $search_item_exists_stmt = DB::getConnection()->prepare($search_item_exists_sql);
            $search_item_exists_stmt->execute([
                'user_id' => $user_id,
                'item_id' => $item_id
            ]);
            $search_item_exists_result = $search_item_exists_stmt->fetch(PDO::FETCH_ASSOC);

            if($search_item_exists_stmt->rowCount() == 0) {
                throw new InvalidItemId();
            }
            else
            {
                $update_fav_sql="UPDATE ITEMS SET favorited=FALSE WHERE user_id=:user_id AND item_id=:item_id";
                $update_fav_stmt=DB::getConnection()->prepare($update_fav_sql);
                $update_fav_stmt->execute([
                    'user_id' => $user_id,
                    'item_id'=>$item_id,
                ]);
            }
        }

    }
?>