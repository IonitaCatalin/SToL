<?php

class CRegister extends Controller {

	private $_model;
	private $_error_msg=null;
	public function __construct() {
		$this->_model = $this->model('mregister');
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
				$_error_msg='Please fill in all the fields';
				$this->_render($_error_msg); 
				}
			else {
				$_error_msg ='';
				$this->_addUser($_POST["email"], $_POST["username"], $_POST["password"]);
				$this->_render($_error_msg);
			}
		}
		else {
			$this->_render();
		}
	}

	public function index() {
		
	}

	private function _addUser($email, $username, $password) {
		$this->_model->addAccount($email, $username, $password);
	}

	private function _render($input_msg = NULL) {
		$this->view('register/vregister');
		$view = new VRegister();
		$view -> loadDataIntoView($input_msg);
		echo $view -> renderView();
	}
}

?>