<?php

class CAdmin extends Controller
{
	private $model;

	public function __construct()
	{
		$this->model=$this->model('madmin');
	}

	public function getStatusForServices()
	{
		try
		{
			$allowed=$this->model->getStatus();
			$json=new JsonResponse('success',$allowed,"Statuses for allowed services retrieved succesfully",200);
			echo $json->response();
		}
		catch(PDOException $exception)
		{
			$json=new JsonResponse('error', null, 'Service temporarly unavailable', 500);
			echo $json->response();
		}
		// $get_services='SELECT * FROM ALLOWED';
		// $json=new JsonResponse('success',["onedrive"=>$GLOBALS['allowed_onedrive'],"googledrive"=>$GLOBALS['allowed_googledrive'],"dropbox"=>$GLOBALS['allowed_dropbox']],"Statuses for allowed services retrieved succesfully",200);
		// echo $json->response();
	}
    public function createCSVFileAndDownloadLink($user_id)
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
                if(isset($post_array['users']))
                {
					$temp_file = uniqid("", true);
					try
					{
						$this->model->createCSVFile($user_id, $post_array['users'], $temp_file);
						$download_url = array('url' => 'http://'. $_SERVER['HTTP_HOST'].'/ProiectTW/api/admin/download_csv/'. $temp_file);
						$json = new JsonResponse('success', $download_url, "Download is starting.", 200);
						echo $json->response();
					}
					catch(PDOException $exception)
					{
						echo $exception;
						$json=new JsonResponse('error', null, 'Service temporarly unavailable', 500);
						echo $json->response();
					}
					catch(Exception $exception)
					{
						$json=new JsonResponse('error', null, 'An unknown error has occured', 403);
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

	public function downloadCSVFile($download_id)
	{
		try
		{
			$this->model->downloadCSVFile($download_id);
		}
		catch(InvalidDownloadId $exception)
		{
			$json=new JsonResponse('error', null, 'Specified download id is invalid',400);
			echo $json->response();
		}
		catch(PDOException $exception)
		{
			echo $exception;
			$json = new JsonResponse('error', null, 'Service temporarly unavailable', 500);
			echo $json->response();
		}
	}

	public function getUsersData($user_id)
	{
        try
        {
            $users_data_json=json_encode($this->model->getUsersData($user_id));
            $json=new JsonResponse('success',$users_data_json,'Users successfully retrieved',200);
            echo $json->response();
        }
        catch(PDOException $exception)
        {
            echo $exception;
            $json=new JsonResponse('error',null,'Service temporarly unavailable',500);
            echo $json->response();
        }
	}
	public function updateAllowFor($service)
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
			if(isset($post_array['allow']))
			{
				$this->model->updateServiceAllow($service,$post_array['allow']);
				$json=new JsonResponse('success',null,'Allow rule updated succesfully',200);
				echo $json->response();
			}
			else
			{
				$json=new JsonResponse('error',null,'Malformed request,JSON data object could not be parsed',400);
                echo $json->response();
			}
		}
	}
}

?>