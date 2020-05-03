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
                    throw new FolderNameTaken();
                }
            }
            else
            {
                throw new InvalidParentId();
            }
        }
    }
?>