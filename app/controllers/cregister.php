<?php

class CRegister extends Controller {

	private $model;

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
				$this->_showPageContent('incomplete_fields_error'); // cand nu s-au completat toate datele
				}
			else {
				$this->_addUser($_POST["email"], $_POST["username"], $_POST["password"]);
				$this->_showPageContent('no_errors');
			}
		}
		else {
			$this->_showPageContent();
		}
	}

	public function index() {
		// nein again
	}

	private function _addUser($email, $username, $password) {
		$this->model->addAccount($email, $username, $password);
	}

	private function _showPageContent($valid_input = NULL) {
		$this->view('register/vregister');
		$view = new VRegister();
		$view -> loadDataIntoView($valid_input);
		echo $view -> offerView();
	}
}

?>