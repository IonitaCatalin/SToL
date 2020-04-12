<?php

class CRegister extends Controller {

	private $model;
	private $error_msg;

	public function __construct() {
		$this->model = $this->model('mregister');
		
		if(isset($_POST["submit_register"]))
		{
			if($_POST["email"] == '' || $_POST["username"] == '' || $_POST["password"] == '') {
				$this->error_msg='Please fill in all the fields.';
				$this->render($this->error_msg); // cand nu s-au completat toate datele
			}
			else {
				$this->error_msg = '';

				$email = $_POST["email"];
				$username = $_POST["username"];
				$password = $_POST["password"];

				if($this->checkExistingEmail($email)){
					$this->error_msg = 'The email address is already in use.';
				}
				else if($this->checkExistingUsername($username)) {
					$this->error_msg = 'The username is not available.';
				}
				else if(strlen($username) < 6) {
					$this->error_msg = 'The username is shorter than 6 characters';
				}
				else if(strlen($password) < 6) {
					$this->error_msg = 'The password is shorter than 6 characters';
				}
				else {
					$this->error_msg= 'Success';
					$this->addUser($_POST["email"], $_POST["username"], $_POST["password"]);
				}

				$this->render($this->error_msg);
			}
			
		}
		else {
			$this->render();
		}
	}

	public function index() {
		// :)
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

	private function checkExistingEmail($email) {
		return $this->model->checkExistingEmail($email);
	}

	private function checkExistingUsername($username) {
		return $this->model->checkExistingUsername($username);
	}
}

?>