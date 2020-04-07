<?php

class CRegister extends Controller {

	private $model;
	private $error_msg;

	public function __construct() {
		$this->model = $this->model('mregister');
		// if(isset($_POST["submit_register"])) // setat doar daca am apasat o data butonul register
		// 	$controller_action = $_POST["submit_register"];

		// if ($actiune == "register") {
		// 	if( $_POST["email"] == '' || $_POST["username"] == '' || $_POST["password"] == '') {
		// 		$this->_showPageContent('incomplet'); // cand nu s-au completat toate datele
		// 	}
		// 	else {
		// 		$this->_addUser($_POST["email"], $_POST["username"], $_POST["password"]);
		// 		$this->_showPageContent('succes');
		// 	}
		// } 
		// else
		// 	$this->_showPageContent();
		if(isset($_POST["submit_register"]))
		{
			if( $_POST["email"] == '' || $_POST["username"] == '' || $_POST["password"] == '') {
				$this->error_msg='Please fill in all the fields';
				$this->render($this->error_msg); // cand nu s-au completat toate datele
				}
			else {
				$this->error_msg=null;
				$this->addUser($_POST["email"], $_POST["username"], $_POST["password"]);
				$this->render($this->error_msg);
			}
		}
		else {
			$this->render();
		}
	}

	public function index() {
		// nein again
	}

	private function addUser($email, $username, $password) {
		$this->model->addAccount($email, $username, $password);
	}

	private function render($error_msg  = NULL) {
		$this->view('register/vregister');
		$view = new VRegister();
		$view -> loadDataIntoView($error_msg);
		echo $view -> loadView();
	}
}

?>