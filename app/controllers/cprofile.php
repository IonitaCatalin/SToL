<?php

class CProfile extends Controller {

	private $model;
	private $auth_error;

	public function __construct() {
		$this->model = $this->model('mprofile');	
	}

	public function preAuthorization($service, $user_id)
	{
		$service=strtolower($service);
		switch($service)
		{
			case 'onedrive':
			{
				$json = new JsonResponse('success', OneDriveService::authorizationRedirectURL($user_id), 'Onedrive Authorization Link', 200);
				echo $json->response();
				break;

			}
			case 'googledrive':
			{
				$json = new JsonResponse('success', GoogleDriveService::authorizationRedirectURL($user_id), 'GoogleDrive Authorization Link', 200);
				echo $json->response();
				break;
			}
			case 'dropbox':
			{
				$json = new JsonResponse('success', DropboxService::authorizationRedirectURL($user_id), 'Dropbox Authorization Link', 200);
				echo $json->response();
				break;
			}	
		}
	}
	public function authorizeServices($service, $code, $user_id)
	{
		$service=strtolower($service);
		switch($service)
		{
			case 'onedrive':
			{
				$this->authorizeServiceOneDrive($code, $user_id);
				break;
			}
			case 'googledrive':
			{
				$this->authorizeServiceGoogleDrive($code, $user_id);
				break;
			}
			case 'dropbox':
			{
				$this->authorizeServiceDropbox($code, $user_id);
				break;
			}	
		}
	}
	public function authorizeServiceOneDrive($code, $user_id)
	{
			try
			{
				$this->model->insertAuthToken(OneDriveService::getAccesRefreshToken($code), $user_id, 'onedrive');
				$json = new JsonResponse('succes',null,'Onedrive service authorized succesfully',200);
				echo $json->response();
			}
			catch(OnedriveAuthException $exception)
			{
				$json = new JsonResponse('error',null,'Authorization process for onedrive service failed: ' . $exception->getMessage());
				echo $json->response();
			}
			catch(PDOException $exception)
			{
				$json = new JsonResponse('error',null,'Service temporarly unavailable',500);
				echo $json->response();
			}
		
	}

	

	public function authorizeServiceGoogleDrive($code, $user_id)
	{
        $global_array = $GLOBALS['array_of_query_string'];

        if(isset($global_array['error'])){
        	$error = $global_array['error'];
        	$json = new JsonResponse('error', null, $error, 500);
			echo $json->response();
			return;
        }
		try
		{
			$decoded_json = GoogleDriveService::getAccesRefreshToken($code);
			$this->model->insertAuthToken($decoded_json, $user_id, 'googledrive');
			$json = new JsonResponse('succes', null, 'GoogleDrive service authorized succesfully', 200);
			echo $json->response();
		}
		catch(GoogledriveAuthException $exception)
		{
			$json = new JsonResponse('error', null, 'Authorization process for googledrive service failed: ' . $exception->getMessage());
			echo $json->response();
		}
		catch(PDOException $exception)
		{
			$json = new JsonResponse('error', null, 'Service temporarly unavailable', 500);
			echo $json->response();
		}

	}


	public function authorizeServiceDropbox($code, $user_id)
	{
        $global_array = $GLOBALS['array_of_query_string'];
        
        if(isset($global_array['error'])){
        	$error = $global_array['error'] . $global_array['error_description'];
        	$json = new JsonResponse('error', null, $error, 500);
			echo $json->response();
			return;
        }
		try
		{
			$decoded_json = DropboxService::getAccesRefreshToken($code);
			$this->model->insertAuthToken($decoded_json, $user_id, 'dropbox');
			$json = new JsonResponse('succes', null, 'Dropbox service authorized succesfully', 200);
			echo $json->response();
		}
		catch(DropboxAuthException $exception)
		{
			$json = new JsonResponse('error', null, 'Authorization process for dropbox service failed: ' . $exception->getMessage());
			echo $json->response();
		}
		catch(PDOException $exception)
		{
			$json = new JsonResponse('error', null, 'Service temporarly unavailable', 500);
			echo $json->response();
		}

	}

	public function getUser($user_id)
	{	
		try
		{
			$data_json=json_encode($this->model->getUserDataArray($user_id));
			$json=new JsonResponse('success',$data_json,'User retrieval succesfully',200);
			echo $json->response();

		}
		catch(PDOException $exception)
		{
			echo $exception;
			$json=new JsonResponse('error',null,'Service temporarly unavailable',500);
			echo $json->response();
		}

	}

	public function changeUserData($user_id)
	{
		$content_type = isset($_SERVER['CONTENT_TYPE']) ? $_SERVER['CONTENT_TYPE'] : '';
        if (stripos($content_type, 'application/json') === false)
        {
            $json=new JsonResponse('error',null,'Only application/json content-type allowed',415);
            echo $json->response();
        }
		else
		{
			$post_data = file_get_contents('php://input');
			$post_array = json_decode($post_data, true);

			if(!is_array($post_array))
			{
				$json = new JsonResponse('error', null, 'Malformed request,JSON data object could not be parsed', 400);
				echo $json->response();
			}
			else if(
				(isset($post_array['username']) == false) && 
				(isset($post_array['oldpassword']) == false) &&
				(isset($post_array['newpassword']) == false) )
				{
					$json = new JsonResponse('error', null, 'Malformed request, required fields are missing', 400);
					echo $json->response();
				}
			else
			{
				try
				{
					if(!empty($post_array['username']))
					{
						try
						{	
							$this->model->updateUsername($post_array['username'], $user_id);
						}
						catch(UsernameTakenException $exception)
						{
							$json=new JsonResponse('error',null,'Username is taken', 409);
							echo $json->response(); 
							die();
						}
					}
					if(!empty($post_array['oldpassword']))
					{
						if(!empty($post_array['newpassword']))
						{
							try
							{
								$this->model->updatePassword($post_array['oldpassword'], $post_array['newpassword'], $user_id);
							}
							catch(IncorrectPasswordException $exception)
							{
								$json=new JsonResponse('error',null,'Given password is incorrect', 422);
								echo $json->response();
								die();
							}
						}
						else
						{
							$json=new JsonResponse('error',null,'Both old password and new password are needed to update user password field', 422);
							echo $json->response(); 
							die();
						}
					}
					$json=new JsonResponse('success', null, 'Data updated succesfully',200);
					echo $json->response();
						
				}
				catch(PDOException $exception)
				{
					$json=new JsonResponse('error',null,'Service temporarly unavailable',500);
				}
			}
		}
	}
		
		

	public function deAuth($service, $user_id)
	{
		try {
			$this->model->invalidateService($user_id, $service);
			$json = new JsonResponse('success', null, 'Service succesfully unauthorized',200);
			echo $json->response();
		}
		catch(PDOException $exception)
		{
			$json=new JsonResponse('error',null,'Service temporarly unavailable',500);
			echo $json->response();
		}
	}

	public function getUserStorageData($user_id)
	{
		try
		{
			$data_json = json_encode($this->model->getUserStorageData($user_id));
			$json = new JsonResponse('success', $data_json, 'User\'s storage data succesfully retrieved', 200);
			echo $json->response();
		}
		catch(PDOException $exception)
		{
			echo $exception;
			$json=new JsonResponse('error', null, 'Storage data temporarily unavailable', 500);
			echo $json->response();
		}
	}

}

?>