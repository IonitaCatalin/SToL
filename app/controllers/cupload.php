<?php
class CUpload extends Controller
{
    private $model;
    public function __construct()
    {
        $this->model=$this->model('mupload');
    }
    public function testFunction($user_id)
    {
        try
        {
            //OneDriveService::downloadFileById($this->model->getAccessToken($user_id,'onedrive'),'C605214351BE1193!1942',$_SERVER['DOCUMENT_ROOT'].'/ProiectTW/downloads/test.bin');
        }
        catch(Exception $exception)
        {
            echo $exception;
        }
    }
    public function createUpload($user_id,$parent_id,$chunk_size)
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
                if(isset($post_array['filename']) && isset($post_array['filesize']) && isset($post_array['mode']))
                {
                    $upload_id=uniqid("",true);
                    try{
                        $this->model->startUpload($upload_id,$user_id,$parent_id,$post_array['filename'],$post_array['filesize'],$post_array['mode']);
                        $upload_url=array('url'=>'http://'.$_SERVER['HTTP_HOST'].'/ProiectTW/api/upload/'.$upload_id,'chunk'=>$chunk_size);
                        $json=new JsonResponse('success',$upload_url,'Upload endpoint generated succesfully',200);
                        echo $json->response();
                    }
                    catch(PDOException $exception)
                    {
                        $json=new JsonResponse('error',null,'Service temporarly unavailable',500);
                        echo $json->response();
                    }
                    catch(InvalidItemParentId $exception)
                    {
                        $json=new JsonResponse('error',null,'Specified parent id is invalid',400);
                        echo $json->response();
                    }
                    catch(ItemNameTaken $exception)
                    {
                        $this->model->deleteIncompleteUpload($upload_id,$fragments_id);
                        $json=new JsonResponse('error',null,'Item name is already taken',409);
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
    public function uploadFile($upload_id,$chunk_size)
    {
        try
        {
            $done=$this->model->appendChunks($upload_id,$chunk_size);
            if($done)
            {
                $this->model->statusChangeToSplitting($upload_id);
                try
                {
                    $bytes=random_bytes(16);
                    $fragments_id=bin2hex($bytes);
                    $this->model->uploadFileWithMode($fragments_id,$upload_id);
                    $this->model->completePublicUpload($upload_id);
                    $json=new JsonResponse('success',null,'Data file uploaded succesfully',201);
                    echo $json->response();
                }
                catch(NotEnoughStorage $exception)
                {
                    $json=new JsonResponse('error',null,'Storage space insufficient to complete request',413);
                    echo $json->response();
                }
                catch(NoStorageServices $exception)
                {
                    $json=new JsonResponse('error',null,'Upload could not be complete since there are no storage services linked',400);
                    echo $json->response();
                }
                catch(GoogledriveUploadFileException $exception)
                {
                    $this->model->deleteIncompleteUpload($upload_id,$fragments_id);
                    $json=new JsonResponse('error',null,'GoogleDrive service failed due to internal issues',400);
                    echo $json->response();
                }
                catch(OneDriveUploadException $exception)
                {
                    $this->model->deleteIncompleteUpload($upload_id,$fragments_id);
                    $json=new JsonResponse('error',null,'Onedrive service failed due to internal issues',400);
                    echo $json->response();
                }
                catch(DropboxUploadFileException $exception)
                {
                    $this->model->deleteIncompleteUpload($upload_id,$fragments_id);
                    $json=new JsonResponse('error',null,'Dropbox service failed due to internal issues',400);
                    echo $json->response();
                }
                catch(InvalidRedundancyException $exception)
                {
                    $this->model->deleteIncompleteUpload($upload_id,$fragments_id);
                    $json=new JsonResponse('error',null,'Using redundancy mode for a file requires at least two services',403);
                    echo $json->response();
                } 
                catch(Exception $exception)
                {
                    $json=new JsonResponse('error',null,'Something went wrong while processing the file',500);
                    echo $json->response();
                } 
                
            }
            else
            {
                $json=new JsonResponse('success',null,'Chunk uploaded successfully',200);  
                echo $json->response();
            }
        }
        catch(PDOException $exception)
        {
            $json=new JsonResponse('error',null,'Service temporarly unavailable',500);
            echo $json->response();
        }
        catch(InvalidUploadId $exception)
        {
            $json=new JsonResponse('error',null,'Invalid upload endpoint',404);
            echo $json->response();
        }
        catch(UnsupportedChunkSize $exception)
        {
            $json=new JsonResponse('error',null,'Chunk size not supported by the server instance',416);
            echo $json->response();
        }
    }
    public function deleteUpload($upload_id)
    {
        try
        {
            $this->model->deletePublicUpload($upload_id);
            $json=new JsonResponse('success',null,'Upload revoked successfully',200);
            echo $json->response();
        }
        catch(PDOException $exception)
        {
            $json=new JsonResponse('error',null,'Service temporarly unavailable',500);
            echo $json->response();
        }
        catch(InvalidUploadId $exception)
        {
            $json=new JsonResponse('error',null,'Invalid upload id supplied',400);
            echo $json->response;
        }
        catch(DeleteSplittingFile $exception)
        {

            $json=new JsonResponse('error',null,'Deleting a file in the process of splitting to associated services is not permitted',400);
            echo $json->response();
        }
    }

}

?>