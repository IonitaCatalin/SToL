<?php
class CUpload extends Controller
{
    private $model;
    public function __construct()
    {
        $this->model=$this->model('mupload');
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
                if(isset($post_array['filename']) && isset($post_array['filesize']))
                {
                    $upload_id=uniqid("",true);
                    try{
                        $this->model->startUpload($upload_id,$user_id,$parent_id,$post_array['filename'],$post_array['filesize']);
                        $upload_url=array('url'=>'http://'.$_SERVER['HTTP_HOST'].'/ProiectTW/api/fileupload/'.$upload_id,'chunk'=>$chunk_size);
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
            $done=$this->model->uploadFile($upload_id,$chunk_size);
            if($done)
            {
                //Inainte de acest raspuns cu 201 v-a venii logica de upload pentru diferite servicii,vei putea sa intrerupi download-ul numai cand se uploadeaza explicit catre server nu si catre servicii
                //Dupa upload-ul pe servicii se va trimite ca si cod de success 201-Content Created
                //Nu vom vrea sa stergem upload-ul curent decat la cererea clientului doar de pe server,de pe servicii nu se va putea in decursul upload-ului
                //Propun ca inainte sa inceapa upload-ul pe servicii sa verificam inca o data existenta sesiune de upload in baza de date ca sa ne asiguram ca user-ul nu a intrerupt sesiunea intre timp astfel sa avem un fail-safe
                $json=new JsonResponse('success',null,'Data file uploaded succesfully',201);
                echo $json->response();
            }
            else
            {
                $json=new JsonResponse('success',null,'Chunk upload successfully',200);  
                echo $json->response();
            }
        }
        catch(PDOException $exception)
        {
            $json=new JsonResponse('error',null,'Service temporarly unavailable',500);
            echo $json->response;
        }
        catch(InvalidUploadId $exception)
        {
            $json=new JsonResponse('error',null,'Invalid upload endpoint',400);
            echo $json->response;
        }
        catch(UnsupportedChunkSize $exception)
        {
            $json=new JsonResponse('error',null,'Chunk size not supported',413);
            echo $json->response;
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
            echo $exception;
            $json=new JsonResponse('error',null,'Service temporarly unavailable',500);
            echo $json->response();
        }
        catch(InvalidUploadId $exception)
        {
            $json=new JsonResponse('error',null,'Invalid upload id supplied',400);
            echo $json->response;
        }
    }

}

?>