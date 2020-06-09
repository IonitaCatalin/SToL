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
	        $redundant_service = $this->model->createDownload($user_id, $file_id, $download_id);
	        $download_url = array('url' => 'http://'. $_SERVER['HTTP_HOST'].'/ProiectTW/api/download/'. $download_id);
	        if($redundant_service === 'onedrive' || $redundant_service === 'dropbox' || $redundant_service === 'googledrive')
	        	$json = new JsonResponse('success', $download_url, "Download is starting. Please wait for us to download the file from " . ucfirst($redundant_service), 200);
	        else
	        	$json = new JsonResponse('success', $download_url, 'Download is starting. Please wait for collecting services fragments', 200);
	        echo $json->response();
	    }
	    catch(InvalidItemId $exception)
	    {
	        $json=new JsonResponse('error', null, 'The provided file id is invalid', 400);
	        echo $json->response();
	    }
		catch(MissingOneDriveAuthException $exception)
		{
			$json=new JsonResponse('error', null, 'Retrieving the file requires OneDrive authorization', 403);
			echo $json->response();
		}
		catch(MissingDropboxAuthException $exception)
		{
			$json=new JsonResponse('error', null, 'Retrieving the file requires Dropbox authorization', 403);
			echo $json->response();
		}
		catch(MissingGoogledriveAuthException $exception)
		{
			$json=new JsonResponse('error', null, 'Retrieving the file requires Googledrive authorization', 403);
			echo $json->response();
		}
		catch(OneDriveMetadataException $exception)
		{
			$json=new JsonResponse('error', null, 'The file fragment from OneDrive is missing', 403);
			echo $json->response();
		}
		catch(DropboxGetFileMetadataException $exception)
		{
			$json=new JsonResponse('error', null, 'The file fragment from Dropbox is missing', 403);
			echo $json->response();
		}
		catch(GoogledriveGetFileMetadataException $exception)
		{
			$json=new JsonResponse('error', null, 'The file fragment from Googledrive is missing', 403);
			echo $json->response();
		}
		catch(RedundantFileDeletedFromAllServicesException $exception)
		{
			$json=new JsonResponse('error', null, 'The redundant file was deleted from all services: '. $exception->getMessage(), 403);
			echo $json->response();
		}
		catch(RedundantFileDownloadMissingAllAuthException $exception)
		{
			$json=new JsonResponse('error', null, 'Please authorize one of the following: '. $exception->getMessage(), 403);
			echo $json->response();
		}
		catch(RedundantFileDownloadMissingServicesAuthException $exception)
		{
			$json=new JsonResponse('error', null, $exception->getMessage(), 403);
			echo $json->response();
		}
	    catch(PDOException $exception)
	    {
	        $json=new JsonResponse('error', null, 'Service temporarly unavailable', 500);
	        echo $json->response();
	    }
	    catch(Exception $exception)
	    {
			$json=new JsonResponse('error', null, 'An unknown error has occured', 403);
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
			$json=new JsonResponse('error', null, 'Specified download id is invalid',400);
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