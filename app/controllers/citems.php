<?php
    class CItems extends Controller
    {
        private $model;
        public function __construct()
        {
            $this->model=$this->model('mitems');
        }
        public function createFolderItem($user_id,$parent_id)
        {
            $content_type = isset($_SERVER['CONTENT_TYPE']) ? $_SERVER['CONTENT_TYPE'] : '';
            if (stripos($content_type, 'application/json') === false) {
                $json=new JsonResponse('error',null,'Only application/json content-type allowed',415);
                echo $json->response();
            }
            else
            {
                $post_data=file_get_contents('php://input');
                $post_array=json_decode($post_data,true);
                if(!is_array($post_array))
                {
                    $json=new JsonResponse('error',null,'Malformed request,JSON data object could not be parsed',400);
                    echo $json->response();
                }
                else
                {
                    if(isset($post_array['foldername']))
                    {
                        try
                        {
                            $this->model->createFolderToParent($user_id,$parent_id,$post_array['foldername']);
                            $json=new JsonResponse('success',null,'Folder item created succesfully',201);
                            echo $json->response();
                        }
                        catch(PDOException $exception)
                        {
                            $json=new JsonResponse('error',null,'Service temporarly unavailable',500);
                            echo $json->response();
                        }
                        catch(ItemNameTaken $exception)
                        {
                            $json=new JsonResponse('error',null,'Folder name already taken in the respective container',409);
                            echo $json->response();
                        }
                        catch(InvalidItemId $exception)
                        {
                            $json=new JsonResponse('error',null,'Specified reference parent id is invalid',400);
                            echo $json->response();
                        }
                    }
                    else 
                    {
                        $json=new JsonResponse('error',null,'Malformed request,required fields are missing',400);
                        echo $json->response();
                    }
                }
            }
        }
        public function createFolderItemToRoot($user_id)
        {
            $content_type = isset($_SERVER['CONTENT_TYPE']) ? $_SERVER['CONTENT_TYPE'] : '';
            if (stripos($content_type, 'application/json') === false) {
                $json=new JsonResponse('error',null,'Only application/json content-type allowed',415);
                echo $json->response();
            }
            else
            {
                $post_data=file_get_contents('php://input');
                $post_array=json_decode($post_data,true);
                if(!is_array($post_array))
                {
                    $json=new JsonResponse('error',null,'Malformed request,JSON data object could not be parsed',400);
                    echo $json->response();
                }
                else
                {
                    if(isset($post_array['foldername']))
                    {
                        try
                        {
                            $this->model->createFolderToRoot($user_id,$post_array['foldername']);
                            $json=new JsonResponse('success',null,'Folder item created succesfully',201);
                            echo $json->response();
                        }
                        catch(PDOException $exception)
                        {
                            $json=new JsonResponse('error',null,'Service temporarly unavailable',500);
                            echo $json->response();
                        }
                        catch(ItemNameTaken $exception)
                        {
                            $json=new JsonResponse('error',null,'Folder name already taken in the respective container with specified id',409);
                            echo $json->response();
                        }
                    }
                    else 
                    {
                        $json=new JsonResponse('error',null,'Malformed request,required fields are missing',400);
                        echo $json->response();
                    }
                }
            }
        }
        public function updateItem($user_id,$item_id)
        {
            $content_type = isset($_SERVER['CONTENT_TYPE']) ? $_SERVER['CONTENT_TYPE'] : '';
            if (stripos($content_type, 'application/json') === false) {
                $json=new JsonResponse('error',null,'Only application/json content-type allowed',415);
                echo $json->response();
            }
            else
            {
                $post_data=file_get_contents('php://input');
                $post_array=json_decode($post_data,true);
                if(!is_array($post_array))
                {
                    $json=new JsonResponse('error',null,'Malformed request,JSON data object could not be parsed',400);
                    echo $json->response();
                }
                else
                {
                    if(isset($post_array['newname']))
                    {
                        try
                        {
                            $this->model->updateItemName($user_id,$item_id,$post_array['newname']);
                            $json=new JsonResponse('success',null,'Data updated successfully',200);
                            echo $json->response();
                        }
                        catch(PDOException $exception)
                        {
                            $json=new JsonResponse('error',null,'Service temporarly unavailable',500);
                            echo $json->response();
                        }
                        catch(ItemNameTaken $exception)
                        {
                            $json=new JsonResponse('error',null,'New name for specified item is already taken',409);
                            echo $json->response();
                        }
                        catch(InvalidItemId $exception)
                        {
                            $json=new JsonResponse('error',null,'Specified reference id is invalid',400);
                            echo $json->response();
                        }
                    }
                    else
                    {
                        $json=new JsonResponse('error',null,'Malformed request,required fields are missing',400);
                        echo $json->response();
                    }
                }
            }
        }
        public function getItemMetadata($user_id,$item_id)
        {
            try
            {
                $data_json=json_encode($this->model->getItemMetadata($user_id,$item_id));
                $json=new JsonResponse('success',$data_json,'Item successfully retrieved',200);
                echo $json->response();
            }
            catch(PDOException $exception)
            {
                $json=new JsonResponse('error',null,'Service temporarly unavailable',500);
                echo $json->response();
            }
            catch(InvalidItemId $exception)
            {
                $json = new JsonResponse('error',null,'Specified reference item id is invalid',400);
                echo $json->response();
            }

        }
        public function getItemsFromRoot($user_id)
        {
            try
            {
                $data_json = json_encode($this->model->getItemsListFromRoot($user_id));
                $json = new JsonResponse('success', $data_json, 'Items succesfully retrieved',200);
                echo $json->response();
            }
            catch(PDOException $exception)
            {
                $json=new JsonResponse('error',null,'Service temporarly unavailable',500);
                echo $json->response();
            }
        }

        public function deleteItem($user_id, $item_id)
        {
            try
            {
                $this->model->deleteItem($user_id, $item_id);
                $json = new JsonResponse('success', null, 'Items succesfully deleted',200);
                echo $json->response();
            }
            catch(OneDriveDeleteException $exception)
            {
                $json=new JsonResponse('error',null,'An unexpected error appeared while deleting file from container Onedrive',500);
                echo $json->response();
            }
            catch(GoogleDriveDeleteException $exception)
            {
                $json=new JsonResponse('error',null,'An unexpected error appeared while deleting file from container GoogleDrive',500);
                echo $json->response();
            }
            catch(DropboxDeleteException $exception)
            {
                $json=new JsonResponse('error',null,'An unexpected error appeared while deleting file from container Dropbox',500);
                echo $json->response();
            }
            catch(PDOException $exception)
            {
            
                $json=new JsonResponse('error',null,'Service temporarly unavailable',500);
                echo $json->response();
            }
            catch(InvalidItemId $exception)
            {
                $json = new JsonResponse('error',null,'Specified reference parent id is invalid',400);
                echo $json->response();
            }
        }

        public function moveItem($user_id, $item_id, $new_parent_id)
        {
            try
            {
                $this->model->moveItem($user_id, $item_id, $new_parent_id);
                $json = new JsonResponse('success', null, 'Item succesfully moved',200);
                echo $json->response();
            }
            catch(PDOException $exception)
            {
                $json=new JsonResponse('error',null,'Service temporarly unavailable',500);
                echo $json->response();
            }
            catch(InvalidItemId $exception)
            {
                $json = new JsonResponse('error',null,'Specified reference item id is invalid',400);
                echo $json->response();
            }
            catch(InvalidItemParentId $exception)
            {
                $json = new JsonResponse('error',null,'Specified reference new parent id is invalid or not a folder',400);
                echo $json->response();
            }
            catch(MoveInvalidNameAndType $exception)
            {
                $json = new JsonResponse('error',null,'There is already an item with same name and type in target directory',409);
                echo $json->response();
            }
        }

        public function searchByName($user_id,$search_name)
        {
            try
            {
                $json=new JsonResponse('success',json_encode($this->model->searchByNameFromRoot($user_id,$search_name)),'Items with specified search criteria retrieved successfully!',200);
                echo $json->response();

            }
            catch(PDOException $exception)
            {
                $json=new JsonResponse('error',null,'Service temporarly unavailable',500);
                echo $json->response();
            }
        }

        public function getFavoritedItems($user_id)
        {
            try
            {
                $data_json=json_encode($this->model->getFavoritedItems($user_id));
                $json=new JsonResponse('success',$data_json,'Favorited items successfully retrieved',200);
                echo $json->response();
            }
            catch(PDOException $exception)
            {
                $json=new JsonResponse('error',null,'Service temporarly unavailable',500);
                echo $json->response();
            }
        }

        public function addToFavorites($user_id, $item_id)
        {
            try
            {
                $this->model->addToFavorites($user_id, $item_id);
                $json=new JsonResponse('success',null,'Item added to favorites successfully',200);
                echo $json->response();
            }
            catch(InvalidItemId $exception)
            {
                $json=new JsonResponse('error',null,'Specified item id is invalid',400);
                echo $json->response();
            }
            catch(PDOException $exception)
            {
                $json=new JsonResponse('error',null,'Service temporarly unavailable',500);
                echo $json->response();
            }
        }

        public function removeFromFavorites($user_id, $item_id)
        {
            try
            {
                $this->model->removeFromFavorites($user_id, $item_id);
                $json=new JsonResponse('success',null,'Item removed from favorites successfully',200);
                echo $json->response();
            }
            catch(InvalidItemId $exception)
            {
                $json=new JsonResponse('error',null,'Specified item id is invalid',400);
                echo $json->response();
            }
            catch(PDOException $exception)
            {
                $json=new JsonResponse('error',null,'Service temporarly unavailable',500);
                echo $json->response();
            }
        }

    }
?>