<?php

class CProfile extends Controller {

	private $model;
	private $data; // ??

	public function __construct() {
		$this->model = $this->model('mprofile');
		
		// PROBLEME CU SESIUNEA, NU SE PASTREAZA DIN LOGIN PANA AICI...
		// if(session_status() == PHP_SESSION_NONE) {
		// 	echo "Nu te-ai autentificat";
		// }
		// else {
		// 	echo "Salut sefu', ce mai faci?";
		// }

		if(isset($_POST["login_action"])){
			if($_POST["login_action"] == "gdrive") {
				echo 'GOOGLA DRAIV HANDLER';
			}
			else if($_POST["login_action"] == "onedrive") {
				echo 'ONE DRIVE HANDLER';
			}
			else if($_POST["login_action"] == "dropbox") {
				echo 'DROPBOX HANDLER';
			}
		}

		$this->render();
	}

	public function index() {
		// :)
	}

	private function render($error_msg  = NULL) {
		$this->view('profile/vprofile');
		$view = new VProfile();
		$view -> loadDataIntoView($error_msg);
		echo $view -> renderView();
	}

}

?>