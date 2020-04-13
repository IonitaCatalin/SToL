<?php

require_once '../app/core/Onedrive.php';
require_once '../app/core/Googledrive.php';

class CProfile extends Controller {

	private $model;
	private $data; 


	public function __construct() {
		$this->model = $this->model('mprofile');	
	}

	public function index() {
		/*
			De fiecare data cand intram pe o pagina noua suntem obligatii sa apelam session_start
		*/
		session_start();
		$this->render();
		echo $_SESSION['USER_ID'];
	}
	
	public function authorizeServiceOneDrive()
	{
			session_start();
			$url =  "//{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
			$escaped_url = htmlspecialchars( $url, ENT_QUOTES, 'UTF-8' );
			$params=parse_url($escaped_url,PHP_URL_QUERY);
			$auth_code=substr($params,strpos($params,'=')+1,strlen($params));
			$this->model->insertAuthToken(OneDriveService::getAccesRefreshToken($auth_code),$_SESSION['USER_ID'],'onedrive');
			header('Location:'.'http://localhost/ProiectTW/public/cprofile');
	}

	public function onedriveAuth()
	{
		session_start();
		if(isset($_SESSION['USER_ID']))
		{
			header('Location:'.OneDriveService::authorizationRedirectURL());
		} else {
			echo 'Nu uita sa faci log in.';
		}
	}

	public function googledriveAuth() {
		session_start();
		if(isset($_SESSION['USER_ID'])) {
			header('Location:'.GoogleDriveService::authorizationRedirectURL());
		} else {
			echo 'Nu uita sa faci log in.';
		}
	}

	public function authorizeServiceGoogleDrive()
	{
		session_start();
		$url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		if(isset($_GET['code'])){
			$decoded_json = GoogleDriveService::getAccesRefreshToken($_GET['code']);
			//GoogleDriveService::removeAccessRefreshToken($decoded_json);
			$this->model->insertAuthToken($decoded_json, $_SESSION['USER_ID'],'googledrive');
			header('Location:'.'http://localhost/ProiectTW/public/cprofile');
		}

		if(isset($_GET['error'])){
			echo "Eroare: " . $_GET['error'] . "<br>";
			die("Nu s-a putut obtine codul pentru cerere.");
		}
	}

	public function user()
	{
		
	}

	private function render($error_msg  = NULL) {
		$this->view('profile/vprofile');
		$view = new VProfile();
		$view -> loadDataIntoView($error_msg);
		echo $view -> renderView();
	}

}

?>