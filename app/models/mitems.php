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




    }
?>