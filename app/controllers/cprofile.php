<?php


class CProfile extends Controller {

	private $model;
	private $auth_error;

	public function __construct() {
		$this->model = $this->model('mprofile');	
	}

	public function preAuthorization($service)
	{
		$service=strtolower($service);
		switch($service)
		{
			case 'onedrive':
			{
				http_response_code(307);
				header('Location:'.OneDriveService::authorizationRedirectURL());
				break;
			}
			case 'googledrive':
			{
				http_response_code(307);
				header('Location:'.GoogleDriveService::authorizationRedirectURL());
				break;
			}
			case 'dropbox':
			{
				http_response_code(307);
				header('Location:'.DropboxService::authorizationRedirectURL());
				break;
			}	
		}
	}
	public function authorizeServices($service, $code)
	{
		$service=strtolower($service);
		switch($service)
		{
			case 'onedrive':
			{
				$this->authorizeServiceOneDrive($code);
				break;
			}
			case 'googledrive':
			{
				$this->authorizeServiceGoogleDrive($code);
				break;
			}
			case 'dropbox':
			{
				$this->authorizeServiceDropbox($code);
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
				//echo $exception->getMessage();
				echo $json->response();
			}
			catch(PDOException $exception)
			{
				$json = new JsonResponse('error',null,'Service temporarly unavailable - Database Unique Token Per User Id Constraint ?',500);
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
			$json = new JsonResponse('error', null, 'Service temporarly unavailable - Database Unique Token Per User Id Constraint ?', 500);
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
			$json = new JsonResponse('error', null, 'Service temporarly unavailable - Database Unique Token Per User Id Constraint ?', 500);
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
			$json=new JsonResponse('error',null,'Service temporarly unavailable',500);
			echo $json->response();
		}

	}
	public function changeUserData($user_id)
	{
			$post_array=json_decode(file_get_contents('php://input'),true);
			if(!is_array($post_array))
            {
                $json=new JsonResponse('error',null,'Malformed request,JSON could not be interpreted',400);
                echo $json->response();
			}
			else
			{
			 try{
				if(isset($post_array['username']))
				{
					try
					{	
						$this->model->updateUsername($post_array['username'], $user_id);
					}
					catch(UsernameTakenException $exception)
					{
						$json=new JsonResponse('error',null,'Username is taken',409);
						echo $json->response();
					}
				}
				if(isset($post_array['newpassword']) && isset($post_array['oldpassword']))
				{
					try
					{
						$this->model->updatePassword($post_array['oldpassword'],$post_array['newpassword'], $user_id);
					}
					catch(IncorrectPasswordException $exception)
					{
						$json=new JsonResponse('error',null,'Given password is incorrect');
						echo $json->response();
					}
				}
				else 
				{
					$json=new JsonResponse('error',null,'Both old password and new password are required',422);
					echo $json->response();
				}
			}
			catch(PDOException $exception)
			{
				$json=new JsonResponse('error',null,'Service temporarly unavailable',500);
			}
			$json=new JsonResponse('succes',null,'Data updated succesfully',200);
			echo $json->response();
		}
	}
	public function deAuth()
	{
		
	}

}

?>