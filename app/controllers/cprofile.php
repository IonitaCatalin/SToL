<?php

require_once '../app/core/Onedrive/Onedrive.php';
require_once '../app/core/Onedrive/OnedriveException.php';
require_once '../app/core/GDrive/Googledrive.php';
require_once '../app/core/JsonResponse.php';
require_once '../app/core/Exceptions/CredentialExceptions.php';

class CProfile extends Controller {

	private $model;
<<<<<<< HEAD
	private $data; 
=======
	private $data;
>>>>>>> eb72603992d1bd687d73c9ea99f6a2eb5b2f1d55

	public function __construct() {
		$this->model = $this->model('mprofile');	
	}

<<<<<<< HEAD
	public function index() {
		session_start();
		if(isset($_SESSION['USER_ID']))
		{
			$this->render();
		}
		else
		{
			header('Location:'."http://{$_SERVER['HTTP_POST']}/ProiectTW/public/clogin");
=======
	public function index()
	{
		session_start();
		if(isset($_SESSION['USER_ID'])) {
			$this->render();
		}
		else {
			//header('Location:'."http://{$_SERVER['HTTP_POST']}/ProiectTW/public/clogin");   // Am comentat liniile de felul acesta deoarece uneori $_SERVER['HTTP_POST'] e null si calea va fi gresita
			header('Location:'.'http://localhost/ProiectTW/public/clogin');
>>>>>>> eb72603992d1bd687d73c9ea99f6a2eb5b2f1d55
		}
	}
	
	public function authorizeServiceOneDrive()
	{
<<<<<<< HEAD
			session_start();
			$url =  "//{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
			$escaped_url = htmlspecialchars( $url, ENT_QUOTES, 'UTF-8' );
			$params=parse_url($escaped_url,PHP_URL_QUERY);
			$auth_code=substr($params,strpos($params,'=')+1,strlen($params));
			try
			{
				$this->model->insertAuthToken(OneDriveService::getAccesRefreshToken($auth_code),$_SESSION['USER_ID'],'onedrive');
				header('Location:'."http://{$_SERVER['HTTP_POST']}/ProiectTW/public/clogin");
			}
			catch(OnedriveAuthException $exception)
			{
				
			}
=======
		session_start();
		$url =  "//{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
		$escaped_url = htmlspecialchars( $url, ENT_QUOTES, 'UTF-8' );
		$params=parse_url($escaped_url,PHP_URL_QUERY);
		$auth_code=substr($params,strpos($params,'=')+1,strlen($params));
		try
		{
			$this->model->insertAuthToken(OneDriveService::getAccesRefreshToken($auth_code),$_SESSION['USER_ID'],'onedrive');
			//header('Location:'."http://{$_SERVER['HTTP_POST']}/ProiectTW/public/clogin");
			header('Location:'.'http://localhost/ProiectTW/public/cprofile');
		}
		catch(OnedriveAuthException $exception)
		{
			echo "$this->code: $this->message"; // ?
		}
>>>>>>> eb72603992d1bd687d73c9ea99f6a2eb5b2f1d55
	}

	public function onedriveAuth()
	{
		session_start();
		if(isset($_SESSION['USER_ID'])) {
			header('Location:'.OneDriveService::authorizationRedirectURL());
		} else {
<<<<<<< HEAD
			header('Location:'."http://{$_SERVER['HTTP_POST']}/ProiectTW/public/clogin");
=======
			//header('Location:'."http://{$_SERVER['HTTP_POST']}/ProiectTW/public/clogin");
			header('Location:'.'http://localhost/ProiectTW/public/clogin');
>>>>>>> eb72603992d1bd687d73c9ea99f6a2eb5b2f1d55
		}
	}

	public function googledriveAuth()
	{
		session_start();
		// click pe Unauthorize dupa ce esti logat pt a vedea fisierele
		if( $this->model->getUserDataArray($_SESSION['USER_ID'])['googledrive'] == true) {
			echo 'Unauthorize is not yet functional. Using this button for tests:)<br>';
			//GoogleDriveService::getAccessTokenAfterRefresh($this->model->getRefreshToken($_SESSION['USER_ID'], 'googledrive')); //teoretic merge, practic aplicatia nu e verificata si nu primesc refresh token-uri de la google:)
			GoogleDriveService::listAllFiles($this->model->getAccessToken($_SESSION['USER_ID'], 'googledrive'));
		}
		else if(isset($_SESSION['USER_ID'])) {
			header('Location:'.GoogleDriveService::authorizationRedirectURL());
		} else {
<<<<<<< HEAD
			header('Location:'."http://{$_SERVER['HTTP_POST']}/ProiectTW/public/clogin");
=======
			//header('Location:'."http://{$_SERVER['HTTP_POST']}/ProiectTW/public/clogin");
			header('Location:'.'http://localhost/ProiectTW/public/clogin');
>>>>>>> eb72603992d1bd687d73c9ea99f6a2eb5b2f1d55
		}
	}

	public function authorizeServiceGoogleDrive()
	{
		session_start();
		$url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		if(isset($_GET['code'])){
			$decoded_json = GoogleDriveService::getAccesRefreshToken($_GET['code']);
			//GoogleDriveService::removeAccessRefreshToken($decoded_json);
<<<<<<< HEAD
			$this->model->insertAuthToken($decoded_json, $_SESSION['USER_ID'],'googledrive');
			header('Location:'."http://{$_SERVER['HTTP_POST']}/ProiectTW/public/cprofile");
=======
			$this->model->insertAuthToken($decoded_json, $_SESSION['USER_ID'], 'googledrive');
			//header('Location:'."http://{$_SERVER['HTTP_POST']}/ProiectTW/public/cprofile");
			header('Location:'.'http://localhost/ProiectTW/public/cprofile');
>>>>>>> eb72603992d1bd687d73c9ea99f6a2eb5b2f1d55
		}

		if(isset($_GET['error'])){
			echo "Eroare: " . $_GET['error'] . "<br>";
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
<<<<<<< HEAD
		elseif($_SERVER['REQUEST_METHOD']=='PUT')
		{
			try
			{
				$this->model->updateUsername('ceva nou');
				$json=new JsonResponse('error',null,'Request primit se astepta procesarea');
				echo $json->response();
			}
			catch(UsernameTakenException $exception)
			{
				$json=new JsonResponse('error',null,$exception->getMessage());
				echo $json->response();
			}
			catch(PDOException $exception)
			{
				$json_response=new JsonResponse('error',null,'Service temporarly unavailable');
				echo $json_response->response();
			}
=======
		elseif($_SERVER['REQUEST_METHOD']=='PATCH')
		{
			
		}
		else 
		{
			$json_response=new JsonResponse('error',null,'Method '.$_SERVER['REQUEST_METHOD'].' is not allowed');
			echo $json_response->response();
>>>>>>> eb72603992d1bd687d73c9ea99f6a2eb5b2f1d55
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