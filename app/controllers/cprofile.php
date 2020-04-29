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
	public function authorizeServices($service,$code)
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
	public function authorizeServiceOneDrive($code)
	{
			try
			{
				$this->model->insertAuthToken(OneDriveService::getAccesRefreshToken($code),$_SESSION['USER_ID'],'onedrive');
				$json=new JsonResponse('succes',null,'Onedrive service authorized succesfully',200);
				echo $json->response();
			}
			catch(OnedriveAuthException $exception)
			{
				$json=new JsonResponse('error',null,'Authorization process for onedrive service failed');
				echo $json->response();
			}
			catch(PDOException $exception)
			{
				$json=new JsonResponse('error',null,'Service temporarly unavailable',500);
				echo $json->response();
			}
		
	}

	

	public function authorizeServiceGoogleDrive($code)
	{
		$url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		if(isset($_GET['code'])){
			$decoded_json = GoogleDriveService::getAccesRefreshToken($code);
			$this->model->insertAuthToken($decoded_json, $_SESSION['USER_ID'], 'googledrive');
		}

		if(isset($_GET['error'])){
			echo "Eroare: " . $_GET['error'] . "<br>";
			die("Nu s-a putut obtine codul pentru cerere.");
		}
	}


	public function authorizeServiceDropbox()
	{
		if(isset($_GET['code'])){
			$decoded_json = DropboxService::getAccesRefreshToken($_GET['code']);
			$this->model->insertAuthToken($decoded_json, $_SESSION['USER_ID'], 'dropbox');
			header('Location:'.'http://localhost/ProiectTW/public/cprofile');
		}
		if(isset($_GET['error'])){
			echo "Eroare: " . $_GET['error'] . "<br>";
			echo "Cod eroare: " . $_GET['error_description'] . "<br>";
			die("Nu s-a putut obtine codul pentru cerere.");
		}
	}

	public function getUser()
	{	
		try
		{
			$data_json=json_encode($this->model->getUserDataArray($_SESSION['USER_ID']));
			$json=new JsonResponse('success',$data_json,'User retrieval succesfully',200);
			echo $json->response();

		}
		catch(PDOException $exception)
		{
			$json=new JsonResponse('error',null,'Service temporarly unavailable',500);
			echo $json->response();
		}

	}
	public function changeUserData()
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
						$this->model->updateUsername($post_array['username'],$_SESSION['USER_ID']);
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
						$this->model->updatePassword($post_array['oldpassword'],$post_array['newpassword'],$_SESSION['USER_ID']);
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