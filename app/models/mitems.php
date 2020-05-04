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
                    $insert_item_sql="INSERT INTO ITEMS VALUES(:user_id,:item_id,'folder')";
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
                    $insert_item_sql="INSERT INTO ITEMS VALUES(:user_id,:item_id,'folder')";
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
            $check_item_existence_sql='SELECT item_id,content_type FROM ITEMS WHERE item_id=:item AND user_id=:user';
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
                    $check_name_sql="SELECT item_id FROM FOLDERS WHERE item_id=:item AND name=:new_name'";
                    $check_name_stmt=DB::getConnection()->prepare($check_name_sql);
                    $check_name_stmt->execute([
                        'item'=>$item_id,
                        'new_name'=>$new_name
                    ]);
                    if($check_name_stmt->rowCount()==0)
                    {
                        $update_name_sql='UPDATE FOLDERS SET name=:new_name WHERE item_id=:item';
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
                else if($result_list['content_type']=='file')
                {
                    $check_name_sql='SELECT item_id FROM FILES WHERE item_id=:item_id AND name=:new_name';
                    $check_name_stmt=DB::getConnection()->prepare($check_name_sql);
                    $check_name_stmt->execute([
                        'item_id'=>$item_id,
                        'new_name'=>$new_name
                    ]);
                    if($check_name_sql->rowCount()==0)
                    {
                        $update_name_sql='UPDATE FILES SET name=:new_name WHERE item_id=:item_id AND user_id=:user_id';
                        $update_name_stmt=DB::getConnection()->prepare($update_name_sql);
                        $update_name_stmt->execute([
                            'new_name'=>$new_name,
                            'item_id'=>$item_id,
                            'user_id'=>$user_id
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

                $list_folders_sql = "SELECT flds.item_id, flds.name, itms.content_type FROM ITEMS itms JOIN FOLDERS flds ON itms.item_id = flds.item_id WHERE user_id=:user_id AND parent_id=:parent_id";
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
  
                $list_files_sql = "SELECT fls.item_id, fls.name, itms.content_type FROM ITEMS itms JOIN FILES fls ON itms.item_id = fls.item_id WHERE user_id=:user_id AND folder_id=:parent_id";
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
            $get_root_sql = "SELECT fold.item_id FROM ITEMS itms JOIN FOLDERS fold ON fold.item_id=itms.item_id WHERE user_id=:id AND parent_id IS NULL";
            $get_root_stmt = DB::getConnection()->prepare($get_root_sql);
            $get_root_stmt->execute([
                'id'=>$user_id,
            ]);

            $root_id = $get_root_stmt->fetch(PDO::FETCH_ASSOC)['item_id'];
            $result_array = array();
            $count = 0;

            $list_folders_sql = "SELECT flds.item_id, flds.name, itms.content_type FROM ITEMS itms JOIN FOLDERS flds ON itms.item_id = flds.item_id WHERE user_id=:user_id AND parent_id=:parent_id";
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

            $list_files_sql = "SELECT fls.item_id, fls.name, itms.content_type FROM ITEMS itms JOIN FILES fls ON itms.item_id = fls.item_id WHERE user_id=:user_id AND folder_id=:parent_id";
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
                $this->deleteFile($item_id);
                return; // !!!
            }

            throw new InvalidItemId();

        }

        public function deleteFile($item_id)
        {
            $delete_file_sql = "DELETE FROM files WHERE item_id=:item_id";
            $delete_file_stmt = DB::getConnection()->prepare($delete_file_sql);
            $delete_file_stmt->execute(['item_id' => $item_id]);
            echo "Deleted $item_id from files.";

            $delete_item_sql = "DELETE FROM items WHERE item_id=:item_id";
            $delete_item_stmt = DB::getConnection()->prepare($delete_item_sql);
            $delete_item_stmt->execute(['item_id' => $item_id]);
            echo "Deleted $item_id from items.";

            $services_ids_sql = "SELECT onedrive_id, dropbox_id, googledrive_id FROM FRAGMENTS WHERE file_id=:item_id";
            $services_ids_stmt = DB::getConnection()->prepare($services_ids_sql);
            $services_ids_stmt->execute(['item_id' => $item_id]);

            // !!!! de tratat situatia cand fisierul e stocat redundant sau nu e stocat simplu(fara tabela fragments sau redundant)
            if($services_ids_stmt->rowCount()>0)
            {
                $row = $services_ids_stmt->fetch(PDO::FETCH_ASSOC);
                if($row['onedrive_id'] != ''){
                    echo "Deleted file $item_id fragment from Onedrive.";
                    // OneDriveService::deleteFileById($row['onedrive_id']);
                }
                if($row['googledrive_id'] != ''){
                    echo "Deleted file $item_id fragment from Googledrive.";
                    // GoogleDriveService::deleteFileById($row['googledrive_id']);
                }
                if($row['dropbox_id'] != ''){
                    echo "Deleted file $item_id fragment from Dropbox.";
                    // DropboxService::deleteFileById($row['dropbox_id']);
                }
                
            }

            $delete_fragment_sql = "DELETE FROM `fragments` WHERE `file_id`=:item_id";
            $delete_fragment_stmt = DB::getConnection()->prepare($delete_fragment_sql);
            $delete_fragment_stmt->execute(['item_id' => $item_id]);
            echo "Deleted $item_id from fragments.";
        }

        public function deleteFolder($user_id, $item_id)
        {
            $files_in_folder = $this->getItemsListFromFolder($user_id, $item_id);
            //print_r($files_in_folder);
            foreach($files_in_folder as $key => $file){ //key e index-ul 0, 1, 2...
                if($file['content_type'] == 'file'){
                    $this->deleteFile($file['item_id']);
                }
                else if($file['content_type'] == 'folder'){
                    $this->deleteFolder($user_id, $file['item_id']);
                }
            }

            $delete_folder_sql = "DELETE FROM folders WHERE item_id=:item_id";
            $delete_folder_stmt = DB::getConnection()->prepare($delete_folder_sql);
            $delete_folder_stmt->execute(['item_id' => $item_id]);
            echo "Deleted $item_id from folders.";

            $delete_item_sql = "DELETE FROM items WHERE item_id=:item_id";
            $delete_item_stmt = DB::getConnection()->prepare($delete_item_sql);
            $delete_item_stmt->execute(['item_id' => $item_id]);
            echo "Deleted $item_id from items.";
        }

        public function moveItem($user_id, $item_id, $new_parent_id)
        {
            echo "MUT $item_id IN $new_parent_id";

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


    }
?>