<?php

class CFiles extends Controller {

	private $model;

	public function __construct() {
		$this->model = $this->model('mfiles');	
	}

	public function index() {
		session_start();
		if(isset($_SESSION['USER_ID'])) {
			$this->render();
		}
		else {
			header('Location:'.'http://localhost/ProiectTW/public/cprofile');
		}
	}

	// functii precum getFilesList iar datele sa ajunga in view ?

	private function render($data = []) {
		$this->view('files/vfiles');
		$view = new VFiles();
		$view -> loadDataIntoView($data);
		echo $view -> renderView();
	}

}


?>