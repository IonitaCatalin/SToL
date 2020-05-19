<?php

class CDownload extends Controller
{
	private $model;

	public function __construct()
	{
		$this->model=$this->model('mdownload');
	}

	public function testFunction($user_id)
	{
		try
		{
			//$this->model->testDownloadGoogledrive($user_id);
			//$this->model->testDownloadDropbox($user_id);
		}
		catch(Exception $exception)
		{
			echo $exception;
		}
	}

	public function createDownload($user_id, $file_id)
	{
		$download_id = uniqid("", true);
	    try
	    {
	        $this->model->createDownload($user_id, $file_id, $download_id);
	        $download_url = array('url' => 'http://'. $_SERVER['HTTP_HOST'].'/ProiectTW/api/download/'. $download_id);
	        $json = new JsonResponse('success', $download_url, 'Download endpoint generated succesfully', 200);
	        echo $json->response();
	    }
	    catch(PDOException $exception)
	    {
	        $json=new JsonResponse('error', null, 'Service temporarly unavailable', 500);
	        echo $json->response();
	    }

	}

	public function downloadFile($download_id)
	{
		try
        {
			$this->model->downloadFile($download_id);
		}
		catch(InvalidDownloadId $exception)
		{
			$json=new JsonResponse('error', null, 'Specified file id is invalid',400);
            echo $json->response();
		}
		catch(PDOException $exception)
        {
            $json = new JsonResponse('error', null, 'Service temporarly unavailable', 500);
            echo $json->response();
        }
	}
}

?>