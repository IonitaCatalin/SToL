<?php

require_once '../app/core/Onedrive.php';

class CProfile extends Controller {

	private $model;
	private $data; 

	

	public function __construct() {
		$this->model = $this->model('mprofile');

		// if(isset($_POST["login_action"])){
		// 	if($_POST["login_action"] == "gdrive") {
		// 		echo 'GOOGLA DRAIV HANDLER';
		// 	}
		// 	else if($_POST["login_action"] == "onedrive") {
		// 		echo 'ONE DRIVE HANDLER';
		// 	}
		// 	else if($_POST["login_action"] == "dropbox") {
		// 		echo 'DROPBOX HANDLER';
		// 	}
		// }

		
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
			$this->model->insertAuthToken(OneDriveService::getAccesRefreshToken($auth_code),$_SESSION['USER_ID']);
			header('Location:'.'http://localhost/ProiectTW/public/cprofile/');
	}

	public function onedriveAuth()
	{
		session_start();
		if(isset($_SESSION['USER_ID']))
		{
			header('Location:'.OneDriveService::authorizationRedirectURL());
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