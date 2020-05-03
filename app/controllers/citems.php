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
                            }
                            catch(PDOException $exception)
                            {
                                $json=new JsonResponse('error',null,'Service temporarly unavailable',500);
                                echo $json->response();
                            }
                            catch(FolderNameTaken $exception)
                            {
                                $json=new JsonResponse('error',null,'Folder name already taken in the respective container with specified id',409);
                                echo $json->response();
                            }
                            catch(InvalidParentId $exception)
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
    }
?>