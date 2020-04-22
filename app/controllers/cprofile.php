<?php

require_once '../app/core/Onedrive/Onedrive.php';
require_once '../app/core/Onedrive/OnedriveException.php';
require_once '../app/core/GDrive/Googledrive.php';
require_once '../app/core/Dropbox/Dropbox.php';
require_once '../app/core/JsonResponse.php';
require_once '../app/core/Exceptions/CredentialsExceptions.php';

class CProfile extends Controller {

	private $model;
	private $data; 

	public function __construct() {
		$this->model = $this->model('mprofile');	
	}

	public function index() {
		session_start();
		if(isset($_SESSION['USER_ID']))
		{
			$this->render();
		}
		else
		{
			header('Location:'.'http://localhost/ProiectTW/public/clogin');
		}
	}
	
	public function authorizeServiceOneDrive()
	{
		session_start();
		$auth_code = $_GET['code'];
		try
		{
			$this->model->insertAuthToken(OneDriveService::getAccesRefreshToken($auth_code),$_SESSION['USER_ID'],'onedrive');
			header('Location:'.'http://localhost/ProiectTW/public/cprofile');
		}
		catch(OnedriveAuthException $exception)
		{
			echo "$this->code: $this->message"; // ?
		}
	}

	public function onedriveAuth()
	{
		session_start();
		if(isset($_SESSION['USER_ID'])) {
			header('Location:'.OneDriveService::authorizationRedirectURL());
		} else {
			header('Location:'.'http://localhost/ProiectTW/public/clogin');
		}
	}

	public function googledriveAuth()
	{
		session_start();
		// click pe Unauthorize dupa ce esti logat pt a vedea fisierele
		if( $this->model->getUserDataArray($_SESSION['USER_ID'])['googledrive'] == true) {
			echo 'Unauthorize is not yet functional. Using this button for tests:)<br>';
			//GoogleDriveService::listAllFiles($this->model->getAccessToken($_SESSION['USER_ID'], 'googledrive'));
			//GoogleDriveService::getFileMetadataById($this->model->getAccessToken($_SESSION['USER_ID'], 'googledrive'), '1jBeVdo4YYPoxrNOVYp3PoCy3NSlQyoiQ');
			// foloseste mai intai list pt a gasi un id
			//GoogleDriveService::downloadFileById($this->model->getAccessToken($_SESSION['USER_ID'], 'googledrive'), '1bVVzi2wwEtx3Xq45l0c7PA2uBwYzlQOk');
			GoogleDriveService::uploadFile($this->model->getAccessToken($_SESSION['USER_ID'], 'googledrive'), null);
		}

		else  if(isset($_SESSION['USER_ID'])) {
			header('Location:'.GoogleDriveService::authorizationRedirectURL());
		} else {
			header('Location:'.'http://localhost/ProiectTW/public/clogin');
		}
	}

	public function authorizeServiceGoogleDrive()
	{
		session_start();
		$url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		if(isset($_GET['code'])){
			$decoded_json = GoogleDriveService::getAccesRefreshToken($_GET['code']);
			$this->model->insertAuthToken($decoded_json, $_SESSION['USER_ID'], 'googledrive');
			header('Location:'.'http://localhost/ProiectTW/public/cprofile');
		}

		if(isset($_GET['error'])){
			echo "Eroare: " . $_GET['error'] . "<br>";
			die("Nu s-a putut obtine codul pentru cerere.");
		}
	}

	public function dropboxAuth()
	{
		session_start();
		if( $this->model->getUserDataArray($_SESSION['USER_ID'])['dropbox'] == true) {
			echo 'Already authorized :)';
		}
		else if(isset($_SESSION['USER_ID'])) {
			header('Location:'. DropboxService::authorizationRedirectURL());
		} else {
			header('Location:'.'http://localhost/ProiectTW/public/clogin');
		}
	}

	public function authorizeServiceDropbox()
	{
		session_start();
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

	public function user()
	{	
		session_start();
		if(!isset($_SESSION['USER_ID'])){
			$json_response=new JsonResponse('error',null,'Access denied for unauthorized user');
			echo $json_response->response();
		}
		//V-a trebuii adaugat si metoda post pentru upload deocamdata merge doar pe recuperarea de date
		elseif($_SERVER['REQUEST_METHOD']=='GET')
		{
				try
				{	
					$result=$this->model->getUserDataArray($_SESSION['USER_ID']);
					$json=new JsonResponse('success',$result,null);
					echo $json->response();
				}
				catch(PDOException $exception)
				{
					$json_response=new JsonResponse('error',null,'Service temporarly unavailable');
					echo $json_response->response();
				}
		}
		elseif($_SERVER['REQUEST_METHOD']=='PUT')
		{
			try
			{
				$put_args=json_decode(file_get_contents("php://input"),true);
				if(isset($put_args['username']) && $put_args['username']!='')
				{
					$this->model->updateUsername($put_args['username'],$_SESSION['USER_ID']);
				}
				if(isset($put_args['oldpass']) && isset($put_args['newpass']))
				{
					$this->model->updatePassword($put_args['oldpass'],$put_args['newpass'],$_SESSION['USER_ID']);
				}
				$json=new JsonResponse('success',null,'Profile data updated succesfully!');
				echo $json->response();
			}
			catch(UsernameTakenException $exception)
			{
				
				$json=new JsonResponse('error',null,$exception->getMessage());
				echo $json->response();
			}
			catch(IncorrectPasswordException $exception)
			{
				$json_response=new JsonResponse('error',null,$exception->getMessage());
				echo $json_response->response();
			}
			catch(PDOException $exception)
			{
				echo $exception->getMessage();
				$json_response=new JsonResponse('error',null,'Service temporarly unavailable');
				echo $json_response->response();
			}
		}
		else 
		{
			$json_response=new JsonResponse('error',null,'Method '.$_SERVER['REQUEST_METHOD'].' is not allowed');
			echo $json_response->response();
		}

	}
	public function deauth($params='')
	{
		session_start();
		if(!isset($_SESSION['USER_ID']))
		{
			$json_response=new JsonResponse('error',null,'Access denied for unauthorized user');
			echo $json_response->response();
		}
		elseif($_SERVER['REQUEST_METHOD']=='DELETE')
		{
			$query = array();
			parse_str($_SERVER['QUERY_STRING'], $query);
				try
				{	
					//$result=$this->model->getUserDataArray($_SESSION['USER_ID']);
					$json=new JsonResponse('success',null,'Service deauthenticated!');
					echo $json->response();
				}
				catch(PDOException $exception)
				{
					$json_response=new JsonResponse('error',null,'Service temporarly unavailable');
					echo $json_response->response();
				}
		}
		else
		{
			$json_response=new JsonResponse('error',null,'Method '.$_SERVER['REQUEST_METHOD'].' is not allowed');
			echo $json_response->response();
		}
		
	}

	private function render($error_msg  = NULL) {
		$this->view('profile/vprofile');
		$view = new VProfile();
		$view -> loadDataIntoView($error_msg);
		echo $view -> renderView();
	}

}

?>